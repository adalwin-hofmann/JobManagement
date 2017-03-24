<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
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

use UserSkill as UserSkillModel;
use UserLanguage as UserLanguageModel;
use UserEducation as UserEducationModel;
use UserAwards as UserAwardsModel;
use UserExperience as UserExperienceModel;
use UserTestimonial as UserTestimonialModel;
use Skill as SkillModel;
use UserCollected as UserCollectedModel;

class UserController extends \BaseController {
    
	public function index() {
		$param['users'] = UserModel::paginate(10);
        $param['pageNo'] = 21;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.user.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 21;
		
	    return View::make('admin.user.create')->with($param);
	}
	
	public function edit($id) {
	    $param['user'] = UserModel::find($id);
        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::all();
        $param['levels'] = LevelModel::all();
        $param['languages'] = LanguageModel::all();
        $param['types'] = TypeModel::all();
        $param['userSkills']  = UserSkillModel::where('user_id', $id)->get();
        $param['userLanguages'] = UserLanguageModel::where('user_id', $id)->get();
        $param['userEducations'] = UserEducationModel::where('user_id', $id)->get();
        $param['userAwards'] = UserAwardsModel::where('user_id', $id)->get();
        $param['userExperiences'] = UserExperienceModel::where('user_id', $id)->get();
        $param['userTestimonials'] = UserTestimonialModel::where('user_id', $id)->get();
	    $param['pageNo'] = 21;
	    
	    return View::make('admin.user.edit')->with($param);
	}
	
	public function store() {

        $rules = [
            'name'       => 'required',
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
            
            if (Input::has('user_id')) {
                $id = Input::get('user_id');
                $user = UserModel::find($id);
                $is_active = Input::get('is_active');
                
                if ($password !== '') {
                    $user->secure_key = md5($company->salt.$password);
                }
                $user->is_active = $is_active;
            } else {
                $user = new CompanyModel;
                
                if ($password === '') {
                    $alert['msg'] = 'You have to enter password';
                    $alert['type'] = 'danger';
                    return Redirect::route('admin.user.create')->with('alert', $alert);
                }
                $user->salt = str_random(8);
                $user->secure_key = md5($user->salt.$password);                
            }

            $password = Input::get('password');
            $id = Input::get('user_id');
            $user = UserModel::find($id);
            $is_active = Input::get('is_active');
            $preview = Input::get('preview');

            if ($password !== '') {
                $user->secure_key = md5($user->salt.$password);
            }

            $user->is_active = $is_active;
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

                    if ($user->is_active) {
                        $scount = SkillModel::where('name', $sname)->get()->count();
                        if ($scount == 0) {
                            $skill = new SkillModel;
                            $skill->name = $sname;
                            $skill->save();
                        }
                    }
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

            $alert['msg'] = 'User has been saved successfully';
            $alert['type'] = 'success';

              
            return Redirect::route('admin.user')->with('alert', $alert);     	        
	    }
	}
	
	public function delete($id) {
	    UserModel::find($id)->delete();
	    
	    $alert['msg'] = 'User has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.user')->with('alert', $alert);
	}

    public function collected() {

        UserCollectedModel::where('email', '')->delete();

        $param['users'] = UserCollectedModel::paginate(10);
        $param['pageNo'] = 22;

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        return View::make('admin.user.collected')->with($param);

    }

    public function deleteCollectedUser($id) {
        UserCollectedModel::find($id)->delete();

        $alert['msg'] = 'User has been deleted successfully';
        $alert['type'] = 'success';

        return Redirect::route('admin.user.collected')->with('alert', $alert);
    }
}
