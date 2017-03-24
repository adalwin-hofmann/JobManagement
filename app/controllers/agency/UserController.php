<?php namespace Agency;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL, Queue;

use User as UserModel;
use Apply as ApplyModel;
use Company as CompanyModel;
use Teamsize as TeamsizeModel;
use City as CityModel;
use Category as CategoryModel;
use CompanyUserNote as CompanyUserNoteModel;
use JobRecommend as JobRecommendModel;
use UserSkill as UserSkillModel;
use Skill as SkillModel;
use CompanyUserScore as CompanyUserScoreModel;
use Job as JobModel;
use CompanyUserInvite as CompanyUserInviteModel;
use Questionnaires as QuestionnairesModel;
use CompanyVITemplate as CompanyVITemplateModel;
use CompanyVICreated as CompanyVICreatedModel;
use Level as LevelModel;
use Language as LanguageModel;
use CompanyCandidates as CompanyCandidatesModel;
use Email as EmailModel;
use Message as MessageModel;
use Label as LabelModel;
use UserLabel as UserLabelModel;


class UserController extends \BaseController {
	
	public function deleteMember($id) {
		CompanyModel::find($id)->delete();
		 
		$alert['msg'] = 'Member has been deleted successfully';
		$alert['type'] = 'success';
		 
		return Redirect::route('agency.dashboard')->with('alert', $alert);
	}


    public  function findPeople() {
        if (!Session::has('agency_id')) {
            return Redirect::route('agency.auth.login');
        }else {

            $startDate = date("Y-01-01");
            $skill_name = '';
            $filter_option = 0;
            $label_option = 0;
            $period = 0;


            if (Input::has('startDate')) {
                $startDate = Input::get('startDate');
            }
            if (Input::has('skill_name')) {
                $skill_name = Input::get('skill_name');
            }

            if (Input::has('filter_option')) {
                $filter_option = Input::get('filter_option');
            }

            if (Input::has('label_option')) {
                $label_option = Input::get('label_option');
            }

            $company = CompanyModel::find(Session::get('agency_id'));

            $param['pageNo'] = 5;
            $result = UserModel::where('created_at','>', $startDate)->where('is_active', 1);

            if ($filter_option == 2) {
                $result = $result->where('category_id', $company->category_id);
            }else if ($filter_option == 1) {
                $userIds = array();
                $userIds[] = 0;
                foreach ($company->jobs as $job) {
                    foreach ($job->applies as $apply) {
                        $userIds[] = $apply->user->id;
                    }
                }

                $result = $result->whereIn('id', $userIds);
            }elseif ($filter_option == 3) {
                $userIds = array();
                $userIds[] = 0;
                foreach ($company->followUsers as $fuser) {
                    $userIds[] = $fuser->user->id;
                }
                $result = $result->whereIn('id', $userIds);
            }

            if ($label_option != 0) {
                $labelIds = array();
                $labelIds[] = 0;
                foreach ($company->userLabels()->where('label_id', $label_option)->get() as $item) {
                    $labelIds[] = $item->user->id;
                }
                $result = $result->whereIn('id', $labelIds);
            }

            if ($skill_name != '') {
                $skills = UserSkillModel::where('name', 'like', '%'.$skill_name.'%')->get();
                $skillIds = array();
                $skillIds[] = 0;
                foreach($skills as $skill) {
                    $skillIds[] = $skill->user_id;
                }
                $result = $result->whereIn('id', $skillIds);
            }

            $param['startDate'] = $startDate;
            $param['skill_name'] = $skill_name;
            $param['filter_option'] = $filter_option;
            $param['label_option'] = $label_option;
            $param['users'] = $result->paginate(PAGINATION_SIZE);
            $param['skills'] = SkillModel::all();
            $param['agency'] = $company;

            $param['questionnaires'] = QuestionnairesModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
            $param['viTemplates'] = CompanyVITemplateModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
            $param['labels'] = LabelModel::all();

            return View::make('agency.user.find')->with($param);
        }
    }


    public  function candidates() {
        if (!Session::has('agency_id')) {
            return Redirect::route('agency.auth.login');
        }else {

            $company = CompanyModel::find(Session::get('agency_id'));

            $param['pageNo'] = 8;
            $param['users'] = CompanyCandidatesModel::whereIn('company_id', $company->companyIds())->get();
            $param['agency'] = $company;
            $param['questionnaires'] = QuestionnairesModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
            $param['viTemplates'] = CompanyVITemplateModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();

            return View::make('agency.user.candidates')->with($param);
        }
    }

    public  function appliedPeople() {
        if (!Session::has('agency_id')) {
            return Redirect::route('agency.auth.login');
        }else {

            $startDate = date("Y-01-01");
            $skill_name = '';
            $previous_position = '';
            $label_option = 0;


            if (Input::has('startDate')) {
                $startDate = Input::get('startDate');
            }
            if (Input::has('skill_name')) {
                $skill_name = Input::get('skill_name');
            }
            if (Input::has('previous_position')) {
                $previous_position = Input::get('previous_position');
            }
            if (Input::has('label_option')) {
                $label_option = Input::get('label_option');
            }


            $company = CompanyModel::find(Session::get('agency_id'));

            $param['pageNo'] = 6;
            $result = UserModel::where('created_at','>', $startDate)->where('is_active', 1);

            $userIds = array();
            $userIds[] = 0;

            foreach ($company->jobs()->get() as $job) {
                foreach ($job->applies as $apply) {
                    $userIds[] = $apply->user->id;
                }
            }

            $result = $result->whereIn('id', $userIds);

            if ($skill_name != '') {
                $skills = UserSkillModel::where('name', 'like', '%'.$skill_name.'%')->get();
                $skillIds = array();
                $skillIds[] = 0;
                foreach($skills as $skill) {
                    $skillIds[] = $skill->user_id;
                }
                $result = $result->whereIn('id', $skillIds);
            }


            if ($label_option != 0) {
                $labelIds = array();
                $labelIds[] = 0;
                foreach ($company->userLabels()->where('label_id', $label_option)->get() as $item) {
                    $labelIds[] = $item->user->id;
                }
                $result = $result->whereIn('id', $labelIds);
            }


            if ($previous_position != '') {
                $users = $result->get();

                $exUserIds = array();
                $exUserIds[] = 0;

                foreach ($users as $user) {
                    foreach ($user->experiences as $item) {
                        if (strpos(strtolower($item->position), strtolower($previous_position)) !== false ){
                            $exUserIds[] = $user->id;
                            break;
                        }

                        if (strpos(strtolower($item->name), strtolower($previous_position)) !== false) {
                            $exUserIds[] = $user->id;
                            break;
                        }
                    }
                }

                $result = $result->whereIn('id', $exUserIds);
            }

            $param['startDate'] = $startDate;
            $param['skill_name'] = $skill_name;
            $param['previous_position'] = $previous_position;
            $param['users'] = $result->paginate(PAGINATION_SIZE);
            $param['skills'] = SkillModel::all();
            $param['agency'] = $company;
            $param['members'] = CompanyModel::whereIn('id', $company->companyIds())->get();
            $param['label_option'] = $label_option;

            $param['questionnaires'] = QuestionnairesModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
            $param['viTemplates'] = CompanyVITemplateModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
            $param['labels'] = LabelModel::all();

            return View::make('agency.user.applied')->with($param);
        }
    }
	
	/* Functions for ajax */
	
	public function asyncUpdateStatus() {
		
		$applyId = Input::get('apply_id');
        $statusValue = Input::get('status_value');
		
		$apply = ApplyModel::find($applyId);
	
		$apply->status = $statusValue;
		$apply->save();
		
		return Response::json(['result' => 'success', 'msg' => 'Status has been updated successfully.']);
		
	}

    public function asyncUpdateHintStatus() {
        $hintId = Input::get('hint_id');

        $hint = JobRecommendModel::find($hintId);

        $hint->status = 1;
        $hint->save();

        return Response::json(['result' => 'success', 'msg' => 'Status has been updated successfully.']);
    }
	
	public function asyncAddMember() {
		if (Session::has('agency_id')) {
			$name = Input::get('name');
			$email = Input::get('email');
            $password = Input::get('password');
			$parentId = Session::get('agency_id');
			
			$count = CompanyModel::where('email', $email)->get()->count();
			
			if ($count == 0) {
				$company = new CompanyModel;
			
					
				$company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
				$company->category_id = CategoryModel::whereRaw(true)->min('id');
				$company->city_id = CityModel::whereRaw(true)->min('id');
				$company->name = $name;
				$company->email = $email;
				$company->logo = 'default_company_logo.gif';
				$company->is_admin = 0;
				$company->parent_id = $parentId;
                $company->is_active = 1;
				$company->is_finished 	= 1;
                $company->salt = str_random(8);
                $company->secure_key = md5($company->salt.$password);

                $company->save();
					
				return Response::json(['result' => 'success', 'msg' => 'Member has been added successfully.']);
			}else {
				return Response::json(['result' => 'fail', 'msg' => 'The email is already registered.']);
			}
		}else {
			return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
		}			
	}
	
	public function asyncUpdateMember() {
		if (Session::has('agency_id')) {
			$name = Input::get('name');
			$email = Input::get('email');
			$parentId = Session::get('agency_id');
			$memberId = Input::get('memberId');
			
			CompanyModel::find($memberId)->delete();
			
			$count = CompanyModel::where('email', $email)->get()->count();
			if ($count == 0) {
				$company = new CompanyModel;
			
			
				$company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
				$company->category_id = CategoryModel::whereRaw(true)->min('id');
				$company->city_id = CityModel::whereRaw(true)->min('id');
				$company->name = $name;
				$company->email = $email;
				$company->logo = 'default_company_logo.gif';
				$company->is_admin = 0;
				$company->parent_id = $parentId;
				$company->is_finished 	= 1;
			
				$company->save();
			
				return Response::json(['result' => 'success', 'msg' => 'Member has been updated successfully.']);
			}else {
				return Response::json(['result' => 'fail', 'msg' => 'The email is already registered.']);
			}			
		}else {
			return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
		}
	}
	
	public function asyncSaveNotes() {
		
		if (Session::has('agency_id')) {
			$userId = Input::get('user_id');
			$notes = Input::get('notes');
			$companyId = Session::get('agency_id');
			
			$count = CompanyUserNoteModel::where('user_id', $userId)->where('company_id', $companyId)->get()->count();
			
			if ($count > 0) {
				$note = CompanyUserNoteModel::where('user_id', $userId)->where('company_id', $companyId)->firstOrFail();
			}else {
				$note = new CompanyUserNoteModel;
			}
			
			$note->user_id = $userId;
			$note->company_id = $companyId;
			$note->notes = $notes;
			
			$note->save();
			
			return Response::json(['result' => 'success', 'msg' => 'Note has been saved successfully.']);
		}else {
			return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
		}
	}

	public function asyncRequestFeedback() {
		if (Session::has('agency_id')) {
			$userId = Input::get('user_id');
			$message_data = Input::get('message');
			$memberId = Input::get('member_id');
			
            Queue::push('\SH\Queue\CompanyRequestFeedbackMessage', ['user_id' => $userId, 'company_id' => Session::get('agency_id'), 'member_id' => $memberId, 'message_data' => $message_data] );
				
			return Response::json(['result' => 'success', 'msg' => 'Request has been sent successfully.']);
		}else {
			return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
		}		
	}


    public function asyncSaveRate() {
        if (Session::has('agency_id')) {
            $userId = Input::get('user_id');
            $score = Input::get('score');

            $count = CompanyUserScoreModel::where('company_id', Session::get('agency_id'))->where('user_id', $userId)->get()->count();

            if ($count > 0) {
                CompanyUserScoreModel::where('company_id', Session::get('agency_id'))->where('user_id', $userId)->delete();
            }

            $userScore = new CompanyUserScoreModel;
            $userScore->company_id = Session::get('agency_id');
            $userScore->user_id = $userId;
            $userScore->score = $score;
            $userScore->save();

            return Response::json(['result' => 'success', 'msg' => 'Score has been saved successfully.']);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }
    
    public function asyncSendMessage() {
		if (Session::has('agency_id')) {
			$userId = Input::get('user_id');
			$message_data = Input::get('message');
			
			$user = UserModel::find($userId);
			$company = CompanyModel::find(Session::get('agency_id'));

            Queue::push('\SH\Queue\CompanyUserApplyMessage', ['user_id' => $userId, 'company_id' => Session::get('agency_id'), 'message_data' => $message_data] );

            $message = new MessageModel;
            $message->user_id = $userId;
            $message->company_id = Session::get('agency_id');
            $message->description = $message_data;
            $message->is_company_sent = TRUE;
            $message->is_read = FALSE;
            $message->save();

            $param['messages'] = MessageModel::whereIn('company_id', $company->companyIds())->where('user_id', $userId)->orderBy('created_at', 'DESC')->get();
				
			return Response::json(['result' => 'success', 'msg' => 'Message has been sent successfully.', 'messageView' => View::make('agency.user.ajaxMessageView')->with($param)->__toString()]);
		}else {
			return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
		}		 
    }


    public function asyncSendInvite() {
        if (Session::has('agency_id')) {
            $userId = Input::get('user_id');
            $jobId = Input::get('job_id');

            $company = CompanyModel::find(Session::get('agency_id'));

            $invite = new CompanyUserInviteModel;
            $invite->user_id = $userId;
            $invite->job_id = $jobId;
            $invite->company_id = $company->id;
            $invite->save();

            Queue::push('\SH\Queue\CompanySendInviteMessage', ['user_id' => $userId, 'company_id' => Session::get('agency_id'), 'job_id' => $jobId] );

            return Response::json(['result' => 'success', 'msg' => 'Invite has been sent successfully.']);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncUserView() {

        if (Session::has('agency_id')) {
            $userId = Input::get('user_id');

            $user = UserModel::find($userId);
            $company = CompanyModel::find(Session::get('agency_id'));

            $param['user'] = $user;
            $param['company'] = $company;
            $param['messages'] = MessageModel::whereIn('company_id', $company->companyIds())->where('user_id', $userId)->orderBy('created_at', 'DESC')->get();

            return Response::json(['result' => 'success', 'userView' => View::make('agency.user.ajaxView')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncAddCandidate() {

        if (Session::has('agency_id')) {

            $user = new UserModel;

            if (UserModel::where('email', Input::get('email'))->get()->count() > 0) {
                return Response::json(['result' => 'fail', 'msg' => 'Email is already registered.']);
            }else {
                $user->name = Input::get('name');
                $user->email = Input::get('email');
                $user->phone = Input::get('phone');
                $user->gender = 0;
                $user->category_id = CategoryModel::whereRaw(true)->min('id');
                $user->city_id = CityModel::whereRaw(true)->min('id');
                $user->level_id = LevelModel::whereRaw(true)->min('id');
                $user->native_language_id = LanguageModel::whereRaw(true)->min('id');
                $user->salt = str_random(8);
                $user->secure_key = md5($user->salt.$user->salt);
                $user->profile_image = LOGO;
                $user->cover_image = DEFAULT_COVER_PHOTO;
                $user->is_active = 1;
                $user->save();


                $companyCandidates = new CompanyCandidatesModel;

                $companyCandidates->user_id = $user->id;
                $companyCandidates->company_id = Session::get('agency_id');

                $companyCandidates->save();


                $note = Input::get('note');

                if ($note != '') {
                    $userNote = new CompanyUserNoteModel;

                    $userNote->user_id = $user->id;
                    $userNote->company_id = Session::get('agency_id');
                    $userNote->notes = $note;

                    $userNote->save();
                }


                $company = CompanyModel::find(Session::get('agency_id'));

                Queue::push('\SH\Queue\CompanyAddCandidateNRMessage', ['user_id' => $user->id, 'company_id' => Session::get('agency_id')] );

                $createdBy = $company->name;
                if ($company->is_admin == 1) $createdBy = 'Admin';

                return Response::json(['result' => 'success', 'msg' => 'Candidate has been added successfully.', 'userId' => $user->id, 'createdBy' => $createdBy, 'userName' => $user->name]);
            }
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }

    }


    public function asyncAddToCandidate() {

        if (Session::has('agency_id')) {

            $userId = Input::get('user_id');

            $companyCandidates = new CompanyCandidatesModel;

            $companyCandidates->user_id = $userId;
            $companyCandidates->company_id = Session::get('agency_id');

            $companyCandidates->save();


            $company = CompanyModel::find(Session::get('agency_id'));
            $user = UserModel::find($userId);

            Queue::push('\SH\Queue\CompanyAddCandidateRMessage', ['user_id' => $user->id, 'company_id' => $company->id] );

            return Response::json(['result' => 'success', 'msg' => 'Candidate has been added successfully.']);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }

    }


    public function asyncSendInterview() {
        if (Session::has('agency_id')) {
            $companyId = Session::get('agency_id');
            $userId = Input::get('user_id');
            $templateId = Input::get('template_id');
            $questionnaireId = Input::get('questionnaire_id');
            $expire_at = Input::get('expire_at');
            $subject = Input::get('subject');
            $description = Input::get('description');

            $viCreated = new CompanyVICreatedModel;

            $viCreated->company_id = $companyId;
            $viCreated->user_id = $userId;
            $viCreated->questionnaire_id = $questionnaireId;
            $viCreated->expire_at = $expire_at;
            $viCreated->token = str_random(10);
            $viCreated->subject = $subject;
            $viCreated->description = $description;

            $viCreated->save();

            Queue::push('\SH\Queue\InterviewSendMessage', ['user_id' => $userId, 'company_id' => $companyId, 'cvc_id' => $viCreated->id] );

            return Response::json(['result' => 'success', 'msg' => trans('job.msg_21')]);

        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncCheckAvailableJobs() {

        if (Session::has('agency_id')) {

            $userId = Input::get('user_id');
            $company = CompanyModel::find(Session::get('agency_id'));

            if ($company->availableJobs($userId)->count() == 0) {
                return Response::json(['result' => 'fail', 'msg' => 'There are no available jobs to move.']);
            }

            $param['user'] = UserModel::find($userId);
            $param['company'] = $company;

            return Response::json(['result' => 'success', 'msg' => 'Candidate has been added successfully.', 'jobsView' => View::make('agency.user.ajaxAvailableJobs')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }

    }

    public function asyncMoveToJob() {

        if (Session::has('agency_id')) {

            $userId = Input::get('user_id');
            $jobId = Input::get('job_id');

            $apply = new ApplyModel;

            $apply->user_id = $userId;
            $apply->job_id = $jobId;

            $apply->save();

            return Response::json(['result' => 'success', 'msg' => 'User moved to job successfully.']);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }

    }

    public function asyncAddLabel() {
        if (Session::has('agency_id')) {

            $userId = Input::get('user_id');
            $labelId = Input::get('label_id');

            $userLabel = new UserLabelModel;

            $userLabel->company_id = Session::get('agency_id');
            $userLabel->user_id = $userId;
            $userLabel->label_id = $labelId;

            $userLabel->save();

            return Response::json(['result' => 'success', 'msg' => 'User Label added successfully.']);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncRemoveLabel() {
        if (Session::has('agency_id')) {

            $userId = Input::get('user_id');
            $labelId = Input::get('label_id');

            UserLabelModel::where('company_id', Session::get('agency_id'))->where('user_id', $userId)->where('label_id', $labelId)->delete();

            return Response::json(['result' => 'success', 'msg' => 'User Label removed successfully.']);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncDetailView() {
        if (Session::has('agency_id')) {

            $userId = Input::get('user_id');

            $param['user'] = UserModel::find($userId);
            $param['agency'] = CompanyModel::find(Session::get('agency_id'));

            return Response::json(['result' => 'success', 'listView' => View::make('agency.user.ajaxUserDetail')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }
    public function asyncAppliedDetailView() {
        if (Session::has('agency_id')) {

            $userId = Input::get('user_id');

            $param['user'] = UserModel::find($userId);
            $param['agency'] = CompanyModel::find(Session::get('agency_id'));

            return Response::json(['result' => 'success', 'listView' => View::make('agency.user.ajaxAppliedUserDetail')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }
}
