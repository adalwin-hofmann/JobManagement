<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL, Queue;
use User as UserModel;
use City as CityModel;
use Category as CategoryModel;
use Level as LevelModel;
use Language as LanguageModel;
use Type as TypeModel;
use Cart as CartModel;
use Apply as ApplyModel;
use Pattern as PatternModel;
use Job as JobModel;
use Company as CompanyModel;
use CompanyUserNote as CompanyUserNoteModel;
use FollowCompany as FollowCompanyModel;
use Admin as AdminModel;
use JobRecommend as JobRecommendModel;
use UserFollowCompany as UserFollowCompanyModel;
use CompanyUserFollow as CompanyUserFollowModel;
use UserSkill as UserSkillModel;
use UserLanguage as UserLanguageModel;
use UserEducation as UserEducationModel;
use UserAwards as UserAwardsModel;
use UserExperience as UserExperienceModel;
use UserTestimonial as UserTestimonialModel;
use Skill as SkillModel;
use UserContact as UserContactModel;
use Email as EmailModel;

class UserController extends \BaseController {


    public function aboutUs() {
        return View::make('user.aboutUs.index');
    }

    public function consumerBasic() {
        return View::make('user.consumerBasic.index');
    }

    public function consumers() {
        return View::make('user.consumers.index');
    }

    public function featureBusinessSmall() {
        return View::make('user.featureBusinessSmall.index');
    }

    public function featureBusiness() {
        return View::make('user.featureBusiness.index');
    }

    public function recommendations() {
        if (!Session::has('user_id')) {
            return Redirect::route('user.auth.login');
        }else {
            $param['pageNo'] = 6;
            $param['user'] = UserModel::find(Session::get('user_id'));
            $param['hints']= $param['user']->hints()->paginate(10);

            return View::make('user.dashboard.recommendations')->with($param);
        }
    }

    public function contacts() {
        if (!Session::has('user_id')) {
            return Redirect::route('user.auth.login');
        }else {
            $param['pageNo'] = 6;
            $param['user'] = UserModel::find(Session::get('user_id'));
            $param['contacts']= $param['user']->contacts()->paginate(10);

            return View::make('user.dashboard.contacts')->with($param);
        }
    }
	
	public function view($slug) {
		
		$user = UserModel::where('slug', '=', $slug)->get();
		$id = $user[0]->id;
		
		if (Session::has('company_id')) {
			
			$c_company = CompanyModel::find(Session::get('company_id'));
			
			$childIds = array();
			
			if ($c_company->is_admin == 1) {
				$parentId = Session::get('company_id');
			}else {
				$parentId = $c_company->parent_id;
				$childIds[] = $parentId;
			}
			
			
			$child_companies = CompanyModel::where('parent_id', $parentId)->get();
			
			foreach ($child_companies as $child_company) {
				$childIds[] = $child_company->id;			
			}

            if (count($childIds) == 0) {
                $param['notes'] = '';
            }else {
                $param['notes'] = CompanyUserNoteModel::whereIn('company_id', $childIds)->where('user_id', $id)->get();
            }

			
			$count = CompanyUserNoteModel::where('company_id', Session::get('company_id'))->where('user_id', $id)->get()->count();
			if ($count > 0) {
				$param['myNote'] = CompanyUserNoteModel::where('company_id', Session::get('company_id'))->where('user_id', $id)->firstOrFail();
			}
			
			$param['companyId'] = Session::get('company_id');
		}
		
		$param['user'] = UserModel::find($id);

        if (Session::has('company_id')) {
            $param['company'] = CompanyModel::find(Session::get('company_id'));
        }

        if (Session::has('agency_id')) {
            $param['agency'] = CompanyModel::find(Session::get('agency_id'));
        }

		return View::make('user.dashboard.view')->with($param);
	}
	
	public function appliedJobs() {
		
		if (!Session::has('user_id')) {
			return Redirect::route('user.auth.login');
		}else {
			$param['pageNo'] = 5;
			$param['user'] = UserModel::find(Session::get('user_id'));
			
			return View::make('user.dashboard.appliedJobs')->with($param);
		}
	}
	
	public function cart($id = 0) {

		if (!Session::has('user_id')) {
			return Redirect::route('user.auth.login');
		}else {
			$param['pageNo'] = 2;
			$param['user'] = UserModel::find(Session::get('user_id'));
			$param['patterns'] = PatternModel::where('user_id', Session::get('user_id'))->orWhereNull('user_id')->get();
            $param['myPatterns'] = PatternModel::where('user_id', Session::get('user_id'))->get();
            $param['statusType'] = $id;
				
			return View::make('user.dashboard.myApply')->with($param);	
		}
	}
	
	public function dashboard($code=NULL) {
		if (!Session::has('user_id')) {
			return Redirect::route('user.auth.login');
		}else {
			$param['pageNo'] = 3;
			$param['user'] = UserModel::find(Session::get('user_id'));
			$param['hints'] = $param['user']->hints()->paginate(10);

            $admin = AdminModel::whereRaw(true)->firstOrFail();
            $param['levelCriteria'] = $admin->level_score;

            if ($code == NULL || $code == 'all') {
                $param['applies'] = $param['user']->applies()->paginate(10);
                $param['applies_state'] = 'all';
            }elseif ($code == 'sent') {
                $param['applies'] = $param['user']->applies()->where('status', 0)->paginate(10);
                $param['applies_state'] = 'sent';
            }elseif ($code == 'read') {
                $param['applies'] = $param['user']->applies()->where('status', 1)->paginate(10);
                $param['applies_state'] = 'read';
            }elseif ($code == 'rejected') {
                $param['applies'] = $param['user']->applies()->where('status', 2)->paginate(10);
                $param['applies_state'] = 'rejected';
            }

            $param['applies_all_count'] = $param['user']->applies()->count();
            $param['applies_sent_count'] = $param['user']->applies()->where('status', 0)->get()->count();
            $param['applies_read_count'] = $param['user']->applies()->where('status', '>=', 1)->where('status', '<>', 2)->get()->count();
            $param['applies_rejected_count'] = $param['user']->applies()->where('status', 2)->get()->count();

			//get newest matching jobs
			$result = JobModel::where('category_id', '=', $param['user']->category_id)->where('is_active', 1);
			
		    $applyJobIds = [];
		    $applyJobIds[] = 0;
			foreach ($param['user']->applies as $apply) {
				$applyJobIds[] = $apply->job_id;
			}
			
			$cartJobIds = [];
			$cartJobIds[] = 0;			
			foreach($param['user']->carts as $cart) {
				$cartJobIds[] = $cart->job_id;
			}
			
			$param['jobs'] = $result->whereNotIn('id', $applyJobIds)
			                        ->whereNotIn('id', $cartJobIds)
		                            ->orderBy('created_at', 'DESC')
			                        ->paginate(10);


            $user = UserModel::find(Session::get('user_id'));
            $userSkills = '';

            foreach ($user->skills as $item) {
                if ($userSkills != '') {
                    $userSkills .= ',';
                }
                $userSkills .= strtolower($item->name);
            }


            $jobs = JobModel::where('is_active', 1)->orderBy('created_at', 'desc')->get();
            $jobIds = [];
            $jobIds[] = 0;

            foreach ($jobs as $jobItem) {
                foreach ($jobItem->skills as $item) {
                    if (strrpos($userSkills, strtolower($item->name)) !== false) {
                        $jobIds[] = $jobItem->id;
                        break;
                    }
                }
            }

            $param['newJobs'] = JobModel::whereIn('id', $jobIds)->get();

			return View::make('user.dashboard.dashboard')->with($param);
		}
	}
	
	
	public function profile() {
		
		if (!Session::has('user_id')) {
			return Redirect::route('user.auth.login');
		}else {
			$param['pageNo'] = 4;
			
			$param['user'] = UserModel::find(Session::get('user_id'));
			$param['cities'] = CityModel::all();
            $param['categories'] = CategoryModel::all();
            $param['levels'] = LevelModel::all();
            $param['languages'] = LanguageModel::all();
            $param['types'] = TypeModel::all();
			$param['userSkills']  = UserSkillModel::where('user_id', Session::get('user_id'))->get();
			$param['userLanguages'] = UserLanguageModel::where('user_id', Session::get('user_id'))->get();
			$param['userEducations'] = UserEducationModel::where('user_id', Session::get('user_id'))->get();
			$param['userAwards'] = UserAwardsModel::where('user_id', Session::get('user_id'))->get();
			$param['userExperiences'] = UserExperienceModel::where('user_id', Session::get('user_id'))->get();
			$param['userTestimonials'] = UserTestimonialModel::where('user_id', Session::get('user_id'))->get();
            $param['followCompanies'] = FollowCompanyModel::all();
            $param['userCompanies'] = UserFollowCompanyModel::where('user_id', Session::get('user_id'))->get();
            $param['skills'] = SkillModel::all();
			
			if ($alert = Session::get('alert')) {
				$param['alert'] = $alert;
			}
			
			return View::make('user.dashboard.profile')->with($param);
		}
	}


	
	
	public function saveProfile() {
		$rules = [
					'name'       => 'required',
					'birthday'   => 'required',
					'year'       => 'numeric',
					'city_id'    => 'required',
					'renumeration_amount' => 'numeric',
					];
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return Redirect::back()
			->withErrors($validator)
			->withInput();
		} else {
			$password = Input::get('password');
			$id = Input::get('user_id');
			$user = UserModel::find($id);
			$preview = Input::get('preview');
		
			if ($password !== '') {
				$user->secure_key = md5($user->salt.$password);
			}
			
			$user->is_active = 1;
			$user->name = Input::get('name');
			$user->email = Input::get('email');
			$user->gender = Input::get('gender');
			$user->birthday = Input::has('birthday') ? Input::get('birthday') : '';
			$user->year = Input::get('year');
			$user->category_id = Input::get('category_id');
			$user->city_id = Input::get('city_id');
			if (Input::hasFile('profile_image')) {
				$filename = str_random(24).".".Input::file('profile_image')->getClientOriginalExtension();
				Input::file('profile_image')->move(ABS_PHOTO_PATH, $filename);
				$user->profile_image = $filename;
			}
			if (Input::hasFile('cover_image')) {
				$filename = str_random(24).".".Input::file('cover_image')->getClientOriginalExtension();
				Input::file('cover_image')->move(ABS_PHOTO_PATH, $filename);
				$user->cover_image = $filename;
			}
			$user->about = Input::has('about') ? Input::get('about') : '';
			$user->professional_title = Input::has('professional_title') ? Input::get('professional_title'): '';
			$user->level_id = Input::get('level_id');
			$user->communication_value = Input::has('communication_value') ? Input::get('communication_value') : 0;
			$user->communication_note =  Input::has('communication_note') ? Input::get('communication_note') : '';
			$user->organisational_value = Input::get('organisational_value');
			$user->organisational_note = Input::has('organisational_note') ? Input::get('organisational_note') : '';
			$user->job_related_value = Input::get('job_related_value');
			$user->job_related_note = Input::has('job_related_note') ? Input::get('job_related_note') : '';
			$user->native_language_id = Input::get('native_language_id');
			$user->hobbies = Input::has('hobbies') ? Input::get('hobbies') : '';
			$user->renumeration_amount = Input::has('renumeration_amount') ? Input::get('renumeration_amount') : 0;
			$user->is_freelance = Input::has('is_freelance') ? Input::get('is_freelance') : 0;
			$user->is_parttime = Input::has('is_parttime') ? Input::get('is_parttime') : 0;
			$user->is_fulltime = Input::has('is_fulltime') ? Input::get('is_fulltime') : 0;
			$user->is_internship = Input::has('is_internship') ? Input::get('is_internship') : 0;
			$user->is_volunteer = Input::has('is_volunteer') ? Input::get('is_volunteer'): 0;
			$user->phone = Input::has('phone') ? Input::get('phone') : '';
			$user->address = Input::has('address') ? Input::get('address') : '';
			$user->website = Input::has('website') ? Input::get('website') : '';
			$user->facebook = Input::has('facebook') ? Input::get('facebook') : '';
			$user->linkedin = Input::has('linkedin') ? Input::get('linkedin') : '';
			$user->twitter = Input:: has('twitter') ? Input::get('twitter') : '';
			$user->google = Input:: has('google') ? Input::get('google') : '';
			$user->lat = Input::get('lat');
			$user->lng = Input::get('lng');
			$user->is_finished = Input::get('is_finished');
			$user->is_published = Input::has('is_published') ? Input::get('is_published') : 0;
		
			$user->save();
		
			UserSkillModel::where('user_id', $user->id)->delete();
			
			if (Input::has('skill_name')) {
				$count = 0;
		
				foreach (Input::get('skill_name') as $sname) {
					
					if ($sname == '') break;
					 
					$skill = new UserSkillModel;
					 
					$skill->user_id = $user->id;
					$skill->name = $sname;
					$skill->value = Input::get('skill_value')[$count];
					 
					$skill->save();
		
					$count ++;
				}
			}
		
			UserLanguageModel::where('user_id', $user->id)->delete();
		
			if (Input::has('foreign_language_id')) {
				$count = 0;
				 
				foreach (Input::get('foreign_language_id') as $lid) {
					
					if ($lid == '') break;
		
					$language = new UserLanguageModel;
		
					$language->language_id = $lid;
					$language->user_id = $user->id;
					$language->understanding = Input::get('understanding')[$count];
					$language->speaking = Input::get('speaking')[$count];
					$language->writing = Input::get('writing')[$count];
		
					$language->save();
		
					$count ++;
				}
			}
		
			UserEducationModel::where('user_id', $user->id)->delete();
		
			if (Input::has('institution_name')) {
				$count = 0;
				 
				foreach(Input::get('institution_name') as $iname) {
					
					if ($iname == '') break;
		
					$education = new UserEducationModel;
		
					$education->user_id = $user->id;
					$education->name = $iname;
					$education->start = Input::get('period_start')[$count];
					$education->end = Input::get('period_end')[$count];
					$education->faculty = Input::get('qualification')[$count];
					$education->notes = Input::get('institution_note')[$count];
					$education->location = Input::get('location')[$count];
		
					$education->save();
		
					$count ++;
		
				}
			}
		
			UserAwardsModel::where('user_id', $user->id)->delete();
		
			if (Input::has('competition_name')) {
				$count = 0;
				 
				foreach (Input::get('competition_name') as $cname) {
					
					if ($cname == '') break;
		
					$awards = new UserAwardsModel;
		
					$awards->user_id = $user->id;
					$awards->name = $cname;
					$awards->prize = Input::get('prize')[$count];
					$awards->year = Input::get('competition_year')[$count];
					$awards->location = Input::get('competition_location')[$count];
		
					$awards->save();
		
					$count ++;
				}
			}
		
			UserExperienceModel::where('user_id', $user->id)->delete();
			
			if (Input::has('organisation_name')) {
				$count = 0;
				 
				foreach (Input::get('organisation_name') as $oname) {
					
					if ($oname == '') break;
		
					$experience = new UserExperienceModel;
		
					$experience->user_id = $user->id;
					$experience->name = $oname;
					$experience->position = Input::get('job_position')[$count];
					$experience->type_id = Input::get('work_job_type')[$count];
					$experience->notes = Input::get('work_note')[$count];
					$experience->start = Input::get('work_period_start')[$count];
					$experience->end = Input::get('work_period_end')[$count];
		
					$experience->save();
		
					$count ++;
				}
				 
			}
			
			UserTestimonialModel::where('user_id', $user->id)->delete();
		
			if (Input::has('testimonial_name')) {
				$count = 0;
			
				foreach (Input::get('testimonial_name') as $tname) {
					
					if ($tname == '') break;
						
					$testimonial = new UserTestimonialModel;
						
					$testimonial->user_id = $user->id;
					$testimonial->name = $tname;
					$testimonial->organisation = Input::get('testimonial_organisation')[$count];
					$testimonial->notes = Input::get('testimonial_note')[$count];
						
					$testimonial->save();
						
					$count ++;
				}
			}

            $cId = array();
            UserFollowCompanyModel::where('user_id', $user->id)->delete();
            if (Input::has('worked_company_id')) {
                foreach (Input::get('worked_company_id') as $wid) {

                    if ($wid == 0) continue;
                    $ufcompany = new UserFollowCompanyModel;

                    $ufcompany->user_id = $user->id;
                    $ufcompany->follow_company_id = $wid;

                    $ufcompany->save();

                    $fCompany = FollowCompanyModel::find($wid);

                    if (!isset($cId[$fCompany->company_id])) {

                        $cId[$fCompany->company_id] = 1;

                        Queue::push('\SH\Queue\UserNotiMessage', ['uf_company_id' => $ufcompany->id, 'f_company_id' => $wid] );
                    }

                }
            }

			$alert['msg'] = 'Profile has been saved successfully';
			$alert['type'] = 'success';
		
			if ($preview == 1) {
				return Redirect::route('user.view', $user->slug);
			}else {
				return Redirect::route('user.dashboard.profile')->with('alert', $alert);
			}
		}
	}
	
	
	/* Functions for ajax */
	public function asyncCreateTemplate() {
		
		$title = Input::get('create_title');
		$description = Input::get('create_description');
		$userId = Session::get('user_id');

		$pattern = new PatternModel;

		$pattern->name = $title;
		$pattern->description = $description;
		$pattern->user_id = $userId;

		$pattern->save();

		return Response::json(['result' => 'success', 'msg' => 'Your template has been saved successfully.']);
	}

    public function asyncEditTemplate() {
        if (!Session::has('user_id')) {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }else {

            $title = Input::get('edit_title');
            $description = Input::get('edit_description');
            $patternId = Input::get('pattern_id');


            $pattern = PatternModel::find($patternId);

            $pattern->name = $title;
            $pattern->description = $description;

            $pattern->save();

            return Response::json(['result' => 'success', 'msg' => 'Your template has been saved successfully.']);
        }
    }

    public function asyncDeleteTemplate() {
        if (!Session::has('user_id')) {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }else {

            $patternId = Input::get('pattern_id');

            $pattern = PatternModel::where('id', $patternId)->delete();

            return Response::json(['result' => 'success', 'msg' => 'Your template has been deleted successfully.']);
        }
    }

    public function asyncFollowCompany() {
        if (!Session::has('user_id')) {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }else {
            $companyId = Input::get('company_id');
            $userId = Session::get('user_id');

            $userFollow = new CompanyUserFollowModel;

            $userFollow->user_id = $userId;
            $userFollow->company_id = $companyId;

            $userFollow->save();

            Queue::push('\SH\Queue\UserFollowCompanyMessage', ['user_id' => $userId, 'company_id' => $companyId] );

            return Response::json(['result' => 'success', 'msg' => 'Your request has been submitted successfully.']);
        }
    }


    public function asyncDeleteHint() {
        if (!Session::has('user_id')) {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }else {
            $hintId = Input::get('hint_id');

            JobRecommendModel::where('id', $hintId)->delete();

            return Response::json(['result' => 'success', 'msg' => 'Your recommendation is deleted successfully.']);
        }
    }

    public function asyncDeleteContacts() {
        if (!Session::has('user_id')) {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }else {
            $contactId = Input::get('contact_id');

            UserContactModel::where('id', $contactId)->delete();

            return Response::json(['result' => 'success', 'msg' => 'Your contact is deleted successfully.']);
        }
    }

    public function asyncSaveContacts() {
        if (!Session::has('user_id')) {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }else {
            $contactId = Input::get('contact_id');
            $name = Input::get('contact_name');
            $contact_email = Input::get('contact_email');
            $phone = Input::get('contact_phone');
            $previousJobs = Input::get('contact_previousJobs');

            $result = UserContactModel::where('email', $contact_email)->where('user_id', Session::get('user_id'));

            if ($contactId != '') {
                $result = $result->where('id', '<>', $contactId);
            }

            $count = $result->get()->count();

            if ($count > 0) {
                return Response::json(['result' => 'fail', 'msg' => 'Email is already exist.']);
            }else {
                $contact = new UserContactModel;

                if ($contactId != '') {
                    $contact = UserContactModel::find($contactId);
                }

                $contact->name = $name;
                $contact->email = $contact_email;
                $contact->phone = $phone;
                $contact->user_id = Session::get('user_id');
                $contact->previousJobs = $previousJobs;

                $contact->save();
            }

            if (UserModel::where('email', $contact_email)->get()->count() == 0) {
                Queue::push('\SH\Queue\UserInvite', ['user_id' => Session::get('user_id'), 'contact_id' => $contact->id] );
            }

            return Response::json(['result' => 'success', 'msg' => 'Your contact has been saved successfully.']);
        }
    }

    public function asyncUnFollowCompany() {
        if (!Session::has('user_id')) {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }else {
            $companyId = Input::get('company_id');
            $userId = Session::get('user_id');

            CompanyUserFollowModel::where('user_id', $userId)->where('company_id', $companyId)->delete();

            Queue::push('\SH\Queue\UserUnFollowCompanyMessage', ['user_id' => $userId, 'company_id' => $companyId] );

            return Response::json(['result' => 'success', 'msg' => 'Your request has been submitted successfully.']);
        }
    }



    public function asyncUpdateScore() {
        if (!Session::has('user_id')) {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }else {
            $userId = Session::get('user_id');

            $admin = AdminModel::whereRaw(true)->firstOrFail();
            $user = UserModel::find($userId);

            $user->score = $user->score + $admin->share_score;
            $user->fb_share = 0;
            $user->save();

            return Response::json(['result' => 'success', 'msg' => 'Your score has been updated successfully.']);
        }
    }

}
