<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL, Queue;

use Company as CompanyModel;
use City as CityModel;
use Category as CategoryModel;
use Apply as ApplyModel;
use JobRecommend as JobRecommendModel;
use ApplyNote as ApplyNoteModel;
use HintNote as HintNoteModel;
use User as UserModel;
use Job as JobModel;
use Level as LevelModel;
use Language as LanguageModel;
use Type as TypeModel;
use Presence as PresenceModel;
use Benefits as BenefitsModel;
use JobSkill as JobSkillModel;
use JobLanguage as JobLanguageModel;
use Questionnaires as QuestionnairesModel;
use CompanyVITemplate as CompanyVITemplateModel;
use CompanyVICreated as CompanyVICreatedModel;
use CompanyVIResponse as CompanyVIResponseModel;
use InterviewNote as InterviewNoteModel;
use Message as MessageModel;
use AgencyShare as AgencyShareModel;
use CompanyShareNote as CompanyShareNoteModel;

class JobController extends \BaseController {
	
	public function index() {
		
		if (!Session::has('company_id')) {
			return Redirect::route('company.auth.login');
		} else {
			$param['pageNo'] = 3;	
			return View::make('company.dashboard.home')->with($param);			
		}
	}
	
	public function view($slug, $state = 5) {
		if (!Session::has('company_id')) {
			return Redirect::route('company.auth.login');
		} else {
			$param['category'] = $state;
			
			$company = CompanyModel::find(Session::get('company_id'));
            $job = JobModel::findBySlug($slug);
			
			if ($company->is_admin == 1) {
				$parentId = $company->id;
			} else {
				$parentId = $company->parentId;
			}

			$param['company'] = $company;
			$param['members'] = CompanyModel::where('parent_id', $parentId)->get();
			$param['job'] = JobModel::where('slug', $slug)->firstOrFail();
            $param['pageNo'] = 2;
            if ($state == 5) {
                $param['applies'] = JobModel::where('slug', $slug)->firstOrFail()->applies()->get();
            } else{
                $param['applies'] = JobModel::where('slug', $slug)->firstOrFail()->applies()->where('status', $state)->get();
            }

            if ($state == 5) {
                $param['hints'] = JobModel::where('slug', $slug)->firstOrFail()->hints()->where('is_verified', 1)->get();
            } else {
                $param['hints'] = JobModel::where('slug', $slug)->firstOrFail()->hints()->where('status', $state)->where('is_verified', 1)->get();
            }

            $param['questionnaires'] = QuestionnairesModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
            $param['viTemplates'] = CompanyVITemplateModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
            
            if ($alert = Session::get('alert')) {
                $param['alert'] = $alert;
            }

            $sharedJob = false;
            if ($job->company_id != $company->id) {
                $agency = CompanyModel::find($job->company_id);
                $agencyShare = AgencyShareModel::where('agency_id', $agency->id)->where('company_id', $company->id)->where('job_id', $job->id)->firstOrFail();
                $sharedJob = true;


                $param['agency'] = $agency;
                $param['agencyShare'] = $agencyShare;
            }
            $param['sharedJob'] = $sharedJob;

			return View::make('company.job.view')->with($param);
		}		
	}
	
	
	public function add() {
		
		if (!Session::has('company_id')) {
			return Redirect::route('company.auth.login');
		} else {
			$param['pageNo'] = 1;
			$param['company_id'] = Session::get('company_id');
			$param['companies'] = CompanyModel::all();
			$param['categories'] = CategoryModel::where('parent_id', NULL)->get();
			$param['presences'] = PresenceModel::all();
			$param['cities'] = CityModel::all();
			$param['languages'] = LanguageModel::all();
			$param['types'] = TypeModel::all();
			$param['levels'] = LevelModel::all();
            $param['jobTemps'] = JobModel::where('company_id', Session::get('company_id'))->where('is_finished', '0')->get();
            $param['company'] = CompanyModel::find(Session::get('company_id'));
			
			if ($alert = Session::get('alert')) {
				$param['alert'] = $alert;
			}
			
			return View::make('company.job.add')->with($param);			
		}
	}
	
	public function doAdd() {
		$rules = ['name' => 'required',
				  'email' => 'required|email',
				  'phone' => 'required',
				  'description' => 'required',
				  'year' => 'numeric',
				  'salary' => 'required|numeric'
				 ];
		
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::back()
            			->withErrors($validator)
            			->withInput();
		} else {
            $is_published = Input::has('is_published') ? Input::get('is_published') : 0;
            $is_name = Input::has('is_name') ? Input::get('is_name') : 1;
            $is_phonenumber = Input::has('is_phonenumber') ? Input::get('is_phonenumber') : 0;
            $is_email = Input::has('is_email') ? Input::get('is_email') : 1;
            $is_currentjob = Input::has('is_currentjob') ? Input::get('is_currentjob') : 0;
            $is_previousjobs = Input::has('is_previousjobs') ? Input::get('is_previousjobs') : 0;
            $is_description = Input::has('is_description') ? Input::get('is_description') : 1;
            $is_verified = Input::has('is_verified') ? Input::get('is_verified') : 1;
            
            $category_id = Input::get('p_category_id');
            if (Input::get('sub_category_id') != '') {
                $category_id = Input::get('sub_category_id');
            }
		
			$job = new JobModel;
		
			$job->company_id = Input::get('company_id');
			$job->name = Input::get('name');
			$job->level_id = Input::get('level_id');
			$job->description = Input::get('description');
			$job->category_id = $category_id;
			$job->presence_id = Input::get('presence_id');
			$job->year = Input::get('year');
			$job->city_id = Input::get('city_id');
			$job->native_language_id = Input::get('native_language_id');
			$job->requirements = Input::get('requirements');
			$job->is_name = $is_name;
			$job->is_phonenumber = $is_phonenumber;
			$job->is_email = $is_email;
			$job->is_currentjob = $is_currentjob;
			$job->is_previousjobs = $is_previousjobs;
			$job->is_description = $is_description;
            $job->is_verified = $is_verified;
			$job->bonus = Input::get('bonus');
			$job->type_id = Input::get('type_id');
			$job->salary = Input::get('salary');
			$job->email = Input::get('email');
			$job->phone = Input::get('phone');
			$job->lat = Input::get('lat');
			$job->long = Input::get('lng');
			$job->is_finished = Input::get('is_finished');
			$job->salary = Input::get('salary');
			$job->paid_after = Input::get('paid_after');
			$job->bonus_description = Input::get('bonus_description');
            $job->by_company = 1;
            $job->is_active = 1;
			$job->save();
			$jobId = $job->id;

		    //save Benefit Names
            if (Input::has('benefit_name')) {
                BenefitsModel::where('job_id', $jobId)->delete();
                foreach (Input::get('benefit_name') as $bname) {
                    $benefits = new BenefitsModel;
                    $benefits->job_id = $jobId;
                    $benefits->name = $bname;
                    $benefits->save();
                }
            }
        
            //save Job Skills
            if (Input::has('skill_name')) {
                JobSkillModel::where('job_id', $jobId)->delete();
                foreach (Input::get('skill_name') as $key => $sname) {
                    $jobskill = new JobSkillModel;
                    $jobskill->job_id = $jobId;
                    $jobskill->name = $sname;
                    $jobskill->value = Input::get('skill_value')[$key];
                    $jobskill->save();
                }
            }
        
            //save Job Foreign Language
            if (Input::has('foreign_language_id')) {
                JobLanguageModel::where('job_id', $jobId)->delete();
                foreach (Input::get('foreign_language_id') as $key => $lid) {
                    $joblanguage = new JobLanguageModel;
                    $joblanguage->job_id = $jobId;
                    $joblanguage->language_id = $lid;
                    $joblanguage->understanding = Input::get('understanding')[$key];
                    $joblanguage->speaking = Input::get('speaking')[$key];
                    $joblanguage->writing = Input::get('writing')[$key];
                    $joblanguage->name = '';
                    $joblanguage->save();
                }
            }

            $company = CompanyModel::find(Session::get('company_id'));

            Queue::push('\SH\Queue\CompanyUserNotiMessage', ['job_id' => $job->id, 'company_id' => $company->id] );
		
		
			$alert['msg'] = 'Job has been saved successfully';
			$alert['type'] = 'success';

			return Redirect::route('company.job.add')->with('alert', $alert);
		}
	}
	
	public function myJobs($id = 0) {
		
		if (!Session::has('company_id')) {
			return Redirect::route('company.auth.login');
		} else {
			$param['pageNo'] = 2;
			if ($alert = Session::get('alert')) {
				$param['alert'] = $alert;
			}
			$c_company = CompanyModel::find(Session::get('company_id'));
			$childIds = array();
			
			if ($c_company->is_admin == 1) {
				$parentId = Session::get('company_id');
			} else {
				$parentId = $c_company->parent_id;
			}
			
			$childIds[] = $parentId;
			
			$child_companies = CompanyModel::where('parent_id', $parentId)->get();
			
			foreach ($child_companies as $child_company) {
				$childIds[] = $child_company->id;
			}

            $cvcs = CompanyVICreatedModel::whereIn('company_id', $childIds)->get();
            $cvcIds = array();
            foreach ($cvcs as $item) {
                if (CompanyVIResponseModel::where('cvc_id', $item->id)->get()->count() > 0) {
                    $cvcIds[] = $item->id;
                }
            }

            if ($id == 5) {

            } elseif ($id == 6) {

            } elseif ($id == 0) {
				$param['jobs'] = JobModel::where('is_finished', '1')->where('by_company', 1)->whereIn('company_id', $childIds)->paginate(PAGINATION_SIZE);
			} elseif ($id < 3) {
				$param['jobs'] = JobModel::where('is_finished', '1')->where('by_company', 1)->whereIn('company_id', $childIds)->where('status', $id)->paginate(PAGINATION_SIZE);
			} else {
                $param['jobs'] = JobModel::where('is_finished', '1')->where('by_company', 1)->whereIn('company_id', $childIds)->where('is_active', 1)->paginate(PAGINATION_SIZE);
            }
			$param['categories'] = CategoryModel::all();
            $param['company'] = CompanyModel::find(Session::get('company_id'));
            $param['statusType'] = $id;
            $param['interviews'] = CompanyVICreatedModel::whereIn('id', $cvcIds)->paginate(PAGINATION_SIZE);
            $param['applies'] = $c_company->applies()->groupBy('user_id')->paginate(PAGINATION_SIZE);
			return View::make('company.job.myjobs')->with($param);			
		}	
	}
	
	/* Functions for ajax */
	
	public function asyncSaveNotes() {
		
		if (Session::has('company_id')) {
			$applyId = Input::get('apply_id');
			$notes = Input::get('notes');
			$companyId = Session::get('company_id');

			$count = ApplyNoteModel::where('company_id', $companyId)->where('apply_id', $applyId)->get()->count();
			
			if ($count > 0) {
				$note = ApplyNoteModel::where('company_id', $companyId)->where('apply_id', $applyId)->firstOrFail();
			} else {
				$note = new ApplyNoteModel;
			}
			
			$note->company_id = $companyId;
			$note->apply_id = $applyId;
			$note->notes = $notes;
			
			$note->save();
			
			return Response::json(['result' => 'success', 'msg' => 'Notes has been saved successfully.']);			
		} else {
			return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
		}
	}
	
	public function asyncSaveHintNotes() {
		
		if (Session::has('company_id')) {
			$hintId = Input::get('hint_id');
			$notes = Input::get('notes');
			$companyId = Session::get('company_id');
		
			$count = HintNoteModel::where('company_id', $companyId)->where('recommend_id', $hintId)->get()->count();
				
			if ($count > 0) {
				$note = HintNoteModel::where('company_id', $companyId)->where('recommend_id', $hintId)->firstOrFail();
			} else {
				$note = new HintNoteModel;
			}
				
			$note->company_id = $companyId;
			$note->recommend_id = $hintId;
			$note->notes = $notes;
				
			$note->save();
				
			return Response::json(['result' => 'success', 'msg' => 'Notes has been saved successfully.']);
		} else {
			return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
		}
	}
	
	public function asyncRejectApply() {
		if (Session::has('company_id')) {
			$applyId = Input::get('apply_id');
            $company = CompanyModel::find(Session::get('company_id'));

            $m_content = 'Your apply has been rejected';
            if ($company->apply_rejection_content != '') {
                $m_content = $company->apply_rejection_content;
            }
			
			$apply = ApplyModel::find($applyId);

            Queue::push('\SH\Queue\CompanyApplyRejectionMessage', ['company_id' => $company->id, 'apply_id' => $applyId, 'm_content' => $m_content] );

			$apply->status = 2;
            $apply->save();
			
			return Response::json(['result' => 'success', 'msg' => 'Apply has been rejected successfully.']);
		} else {
			return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
		}
	}
	
	
	public function asyncRejectHint() {
		if (Session::has('company_id')) {
			$hintId = Input::get('hint_id');
            $company = CompanyModel::find(Session::get('company_id'));

            $m_content = 'Your hint has been rejected';
            if ($company->hint_rejection_content != '') {
                $m_content = $company->hint_rejection_content;
            }
				
			$hint = JobRecommendModel::find($hintId);

            Queue::push('\SH\Queue\CompanyHintRejectionMessage', ['company_id' => $company->id, 'hint_id' => $hintId, 'm_content' => $m_content] );
				
			JobRecommendModel::find($hintId)->delete();
				
			return Response::json(['result' => 'success', 'msg' => 'Hint has been rejected successfully.']);
		} else {
			return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
		}		
	}
	
	public function asyncSendMessage() {
		$applyId = Input::get('apply_id');
		$message_data = Input::get('message');

        $apply = ApplyModel::find($applyId);

        $userId = $apply->user_id;
        $jobId = $apply->job_id;

		$user = UserModel::find($userId);

        if ($jobId != '') {
            $job = JobModel::find($jobId);
        }

		$company = CompanyModel::find(Session::get('company_id'));

        if ($jobId != '') {
            Queue::push('\SH\Queue\CompanyUserMessage', ['user_id' => $user->id, 
                                                         'job_id' => $jobId, 
                                                         'company_id' => $company->id, 
                                                         'message_data' => $message_data, ] );
        } else {
            Queue::push('\SH\Queue\CompanyUserApplyMessage', ['user_id' => $userId, 
                                                              'company_id' => Session::get('agency_id'), 
                                                              'message_data' => $message_data, ] );
        }
		
		$message = new MessageModel;
        if ($jobId != '') {
            $message->job_id = $jobId;
        }
		$message->user_id = $userId;
		$message->company_id = Session::get('company_id');
		$message->description = $message_data;
		$message->is_company_sent = TRUE;
		$message->is_read = FALSE;
		$message->save();
		
		return Response::json(['result' => 'success', 'msg' => 'Message has been sent successfully.']);
	}
	
	public function asyncSendMessageHint() {
	
		$hintId = Input::get('hint_id');
		$message_data = Input::get('message');
	
		$hint = JobRecommendModel::find($hintId);
		$job = JobModel::find($hint->job_id);
		$company = CompanyModel::find(Session::get('company_id'));

        Queue::push('\SH\Queue\CompanyUserMessage', ['user_id' => $hint->user->id, 
                                                     'company_id' => $company->id, 
                                                     'job_id' => $job->id, 
                                                     'message_data' => $message_data] );
		
	    $message = new MessageModel;
	    $message->job_id = $hint->job->id;
	    $message->user_id = $hint->user_id;
	    $message->company_id = Session::get('company_id');
	    $message->description = $message_data;
	    $message->is_company_sent = TRUE;
	    $message->is_read = FALSE;
	    $message->save();

		return Response::json(['result' => 'success', 'msg' => 'Message has been sent successfully.']);
	}

    public function asyncSaveApplyRate() {
        if (Session::has('company_id')) {
            $applyId = Input::get('apply_id');
            $score = Input::get('score');

            $apply = ApplyModel::find($applyId);
            $apply->score = $score;
            $apply->save();

            return Response::json(['result' => 'success', 'msg' => 'Score has been saved successfully.']);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncSaveHintRate() {
        if (Session::has('company_id')) {
            $applyId = Input::get('hint_id');
            $score = Input::get('score');

            $hint = JobRecommendModel::find($applyId);
            $hint->score = $score;
            $hint->save();

            return Response::json(['result' => 'success', 'msg' => 'Score has been saved successfully.']);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncUpdateStatus() {
        if (Session::has('company_id')) {
            $jobId = Input::get('job_id');
            $status = Input::get('status');

            $job = JobModel::find($jobId);

            if ($status < 3) {
                $job->status = $status;
                $job->is_active = 1;
            } elseif ($status == 3) {
                $job->is_active = 1;
            } elseif ($status == 4) {
                $job->is_active = 0;
            }

            $job->save();

            return Response::json(['result' => 'success', 'msg' => 'Status has been updated successfully.']);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }
    
    public function asyncUpdateBonus() {
        if (Session::has('company_id')) {
            $jobId = Input::get('job_id');
            $bonus = Input::get('bonus');
    
            $job = JobModel::find($jobId);
            $job->bonus = $bonus;
            $job->save();
    
            return Response::json(['result' => 'success', 'msg' => 'Bonus has been updated successfully.']);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }    


    public function asyncSendInterview() {
        if (Session::has('company_id')) {
            $jobId = Input::get('job_id');
            $companyId = Session::get('company_id');
            $userId = Input::get('user_id');
            $templateId = Input::get('template_id');
            $questionnaireId = Input::get('questionnaire_id');
            $expire_at = Input::get('expire_at');
            $subject = Input::get('subject');
            $description = Input::get('description');

            $viCreated = new CompanyVICreatedModel;

            $viCreated->company_id = $companyId;
            $viCreated->job_id = $jobId;
            $viCreated->user_id = $userId;
            $viCreated->questionnaire_id = $questionnaireId;
            $viCreated->expire_at = $expire_at;
            $viCreated->token = str_random(10);
            $viCreated->subject = $subject;
            $viCreated->description = $description;

            $viCreated->save();

            $applyId = Input::get('apply_id');
            $statusValue = Input::get('status_value');

            $apply = ApplyModel::find($applyId);

            $apply->status = $statusValue;
            $apply->save();


            $cUser = UserModel::find($userId);
            $company = CompanyModel::find($companyId);
            $job = JobModel::find($jobId);


            Queue::push('\SH\Queue\InterviewSendMessage', ['user_id' => $userId, 'company_id' => $company->id, 'cvc_id' => $viCreated->id] );

            return Response::json(['result' => 'success', 'msg' => trans('job.msg_21')]);

        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncSaveInterviewNote() {
        if (Session::has('company_id')) {
            $companyId = Session::get('company_id');
            $cvrId = Input::get('cvr_id');
            $notes = Input::get('notes');

            $interviewNote = new InterviewNoteModel;

            if (InterviewNoteModel::where('company_id', $companyId)->where('cvr_id', $cvrId)->get()->count() > 0) {
                $interviewNote = InterviewNoteModel::where('company_id', $companyId)->where('cvr_id', $cvrId)->firstOrFail();
            }

            $interviewNote->company_id = $companyId;
            $interviewNote->cvr_id = $cvrId;
            $interviewNote->notes = $notes;

            $interviewNote->save();

            return Response::json(['result' => 'success', 'msg' => trans('company.msg_43')]);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncSaveShareNote() {
        if (Session::has('company_id')) {
            $companyId = Session::get('company_id');
            $shareId = Input::get('share_id');
            $note = Input::get('note');

            $shareNote = new CompanyShareNoteModel;

            if (CompanyShareNoteModel::where('share_id', $shareId)->where('company_id', $companyId)->get()->count() > 0) {
                $shareNote = CompanyShareNoteModel::where('share_id', $shareId)->where('company_id', $companyId)->firstOrFail();
            }

            $shareNote->share_id = $shareId;
            $shareNote->company_id = $companyId;
            $shareNote->note = $note;
            $shareNote->save();

            return Response::json(['result' => 'success', 'msg' => 'Note has been saved successfully.']);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }
}
