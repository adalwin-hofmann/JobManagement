<?php namespace Agency;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL, Queue;

use Company as CompanyModel;
use City as CityModel;
use Category as CategoryModel;
use Teamsize as TeamsizeModel;
use Service as ServiceModel;
use CompanyService as CompanyserviceModel;


use Job as JobModel;
use Level as LevelModel;
use Language as LanguageModel;
use Type as TypeModel;
use Presence as PresenceModel;
use Benefits as BenefitsModel;
use JobSkill as JobSkillModel;
use JobLanguage as JobLanguageModel;
use FollowCompany as FollowCompanyModel;
use Questions as QuestionsModel;
use Questionnaires as QuestionnairesModel;
use QuestionnaireQuestions as QuestionnaireQuestionsModel;
use CompanyVITemplate as CompanyVITemplateModel;
use CompanyVICreated as CompanyVICreatedModel;
use CompanyApply as CompanyApplyModel;
use CompanyApplyNote as CompanyApplyNoteModel;
use Message as MessageModel;
use User as UserModel;
use Email as EmailModel;

class AgencyController extends \BaseController {
	
	public function index() {
		
		if (!Session::has('agency_id')) {
			return Redirect::route('agency.auth.login');
		}else {
			$param['pageNo'] = 3;	
			
			$startDate = date("Y-m-d", strtotime("-3 Months"));
			$endDate = date("Y-m-d");
			$period = 90;

			if (Input::has('startDate')) {
				$startDate = Input::get('startDate');
			}
			if (Input::has('endDate')) {
				$endDate = Input::get('endDate');
			}
			if (Input::has('period')) {
				$period = Input::get('period');

                $startDate = date('Y-m-d', strtotime('-'.$period.' days', strtotime($endDate)));
			}
			
			$agency = CompanyModel::find(Session::get('agency_id'));
            $param['members'] = CompanyModel::whereIn('id', $agency->companyIds())->get();

			$param['startDate'] = $startDate;
			$param['endDate'] = $endDate;
			$param['period'] = $period;
			$param['agency'] = $agency;

            $param['questionnaires'] = QuestionnairesModel::where('company_id', $agency->id)->orWhere('by_admin', 1)->get();
            $param['viTemplates'] = CompanyVITemplateModel::where('company_id', $agency->id)->orWhere('by_admin', 1)->get();

			return View::make('agency.dashboard.home')->with($param);
		}
	}
	
	
	public function profile() {
		if (!Session::has('agency_id')) {
			return Redirect::route('agency.auth.login');
		}else {
			$param['pageNo'] = 4;
			$param['agency'] = CompanyModel::find(Session::get('agency_id'));
			$param['cities'] = CityModel::all();
			$param['teamsizes'] = TeamsizeModel::all();
			$param['categories'] = CategoryModel::all();
			$param['services']  = ServiceModel::all();
			$param['companyServices']  = CompanyServiceModel::where('company_id', Session::get('agency_id'))->get();
            $param['questions'] = QuestionsModel::where('company_id', Session::get('agency_id'))->orWhere('by_admin', 1)->get();
            $param['questionnaires'] = QuestionnairesModel::where('company_id', Session::get('agency_id'))->orWhere('by_admin', 1)->get();
            $param['templates'] = CompanyVITemplateModel::where('company_id', Session::get('agency_id'))->get();
			
			if ($alert = Session::get('alert')) {
				$param['alert'] = $alert;
			}
			
			return View::make('agency.dashboard.profile')->with($param);
		}
	}
	
	public function saveProfile() {

		$rules = ['name' 		=> 'required',
				  'email' 		=> 'required|email',
				  'year' 		=> 'numeric',
				  'service_id'	=> 'required',
				  ];
		
		$validator = Validator::make(Input::all(), $rules);
		 
		if ($validator->fails()) {
			return Redirect::back()
			->withErrors($validator)
			->withInput();
		} else {
			$password = Input::get('password');
			$companyId = 0;
		
			$is_published = 0;
		
			if (Input::has('is_published')) {
				$is_published = Input::get('is_published');
			}
		
			if (Input::has('company_id')) {
				$id = Input::get('company_id');
				$company = CompanyModel::find($id);
				$is_active = Input::get('is_active');
		
				if ($password !== '') {
					$company->secure_key = md5($company->salt.$password);
				}
				$company->is_active = 1;
		
				$companyId = $id;
		
			} else {
				$company = new CompanyModel;
		
				if ($password === '') {
					$alert['msg'] = 'You have to enter password';
					$alert['type'] = 'danger';
					return Redirect::route('admin.company.create')->with('alert', $alert);
				}
				$company->salt = str_random(8);
				$company->secure_key = md5($company->salt.$password);
			}
		
			$company->name 			= Input::get('name');
			$company->tag 			= Input::get('tag');
			$company->year 			= Input::get('year');
			$company->teamsize_id 	= Input::get('teamsize_id');
			$company->category_id 	= Input::get('category_id');
			$company->city_id		= Input::get('city_id');
			$company->description 	= Input::get('description');
			$company->expertise 	= Input::get('expertise');
			$company->address 		= Input::get('address');
			$company->email 		= Input::get('email');
			$company->phone 		= Input::get('phone');
			$company->website 		= Input::get('website');
			$company->facebook 		= Input::get('facebook');
			$company->linkedin 		= Input::get('linkedin');
			$company->twitter		= Input::get('twitter');
			$company->google 		= Input::get('google');
			$company->lat 			= Input::get('lat');
			$company->long			= Input::get('lng');
            $company->apply_rejection_title = Input::get('apply_rejection_title');
            $company->apply_rejection_content = Input::get('apply_rejection_content');
            $company->hint_rejection_title = Input::get('hint_rejection_title');
            $company->hint_rejection_content = Input::get('hint_rejection_content');
			$company->is_published  = $is_published;
			$company->is_finished 	= 1;
            $company->is_show       = Input::has('is_show') ? Input::get('is_show') : 0;
            $company->overlay_color = Input::get('overlay_color');
            $company->video_interview_text = input::get('video-interview-text');
            $company->video_interview_end = input::get('video-interview-end');

			if (Input::hasFile('logo')) {
				$filename = str_random(24).".".Input::file('logo')->getClientOriginalExtension();
				Input::file('logo')->move(ABS_LOGO_PATH, $filename);
				$company->logo = $filename;
			}

            if (Input::hasFile('video-interview-logo')) {
                $filename = str_random(24).".".Input::file('video-interview-logo')->getClientOriginalExtension();
                Input::file('video-interview-logo')->move(ABS_LOGO_PATH, $filename);
                $company->video_interview_logo = $filename;
            }

            if (Input::hasFile('video-interview-image')) {
                $filename = str_random(24).".".Input::file('video-interview-image')->getClientOriginalExtension();
                Input::file('video-interview-image')->move(ABS_COMPANY_PHOTO_PATH, $filename);
                $company->video_interview_image = $filename;
            }

            if (Input::hasFile('video-interview-background')) {
                $filename = str_random(24).".".Input::file('video-interview-background')->getClientOriginalExtension();
                Input::file('video-interview-background')->move(ABS_COMPANY_PHOTO_PATH, $filename);
                $company->video_interview_background = $filename;
            }

            if (Input::hasFile('agency_sharing_logo')) {
                $filename = str_random(24).".".Input::file('agency_sharing_logo')->getClientOriginalExtension();
                Input::file('agency_sharing_logo')->move(ABS_LOGO_PATH, $filename);
                $company->agency_sharing_logo = $filename;
            }

            if (Input::hasFile('agency_sharing_background')) {
                $filename = str_random(24).".".Input::file('agency_sharing_background')->getClientOriginalExtension();
                Input::file('agency_sharing_background')->move(ABS_COMPANY_PHOTO_PATH, $filename);
                $company->agency_sharing_background = $filename;
            }

			$company->save();
		
			if ($companyId == 0) $companyId = $company->id;
		
			$count = 0;
		
			CompanyserviceModel::where('company_id', $companyId)->delete();
		
			foreach (Input::get('service_id') as $sId) {
				
				if ($sId == '') break;
				
				$companyService = new CompanyserviceModel;
				 
				$companyService->company_id = $companyId;
				$companyService->service_id = $sId;
				$companyService->description = Input::get('service_description')[$count];
				 
				$companyService->save();
				$count ++;
			}

            FollowCompanyModel::where('company_id', $company->id)->delete();
            if (Input::has('follow_company_name')) {
                foreach (Input::get('follow_company_name') as $fname) {
                    $fCompany = new FollowCompanyModel;

                    $fCompany->company_id = $company->id;
                    $fCompany->name = $fname;
                    $fCompany->status = 1;

                    $fCompany->save();
                }
            }
		
			$alert['msg'] = 'Profile has been saved successfully';
			$alert['type'] = 'success';
		
			return Redirect::route('agency.profile')->with('alert', $alert);
		}
	}


    // ajax functions
    public function asyncSaveQuestion(){
        if (Session::has('agency_id')) {
            $companyId = Input::get('agency_id');
            $question = Input::get('question');
            $time = Input::get('time');

            $questions = new QuestionsModel;

            if (QuestionsModel::where('company_id', $companyId)->where('question', $question)->get()->count() > 0) {
                $questions = QuestionsModel::where('company_id', $companyId)->where('question', $question)->firstOrFail();
            }

            $questions->company_id = $companyId;
            $questions->question = $question;
            $questions->by_admin = 0;
            $questions->time = $time;

            $questions->save();

            return View::make('company.dashboard.saveQuestionsAjax')->with('questions', QuestionsModel::where('company_id', $companyId)->orWhere('by_admin', 1)->get());
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncDeleteQuestion(){
        if (Session::has('agency_id')) {
            $companyId = Input::get('agency_id');
            $question = Input::get('question');

            QuestionsModel::where('company_id', $companyId)->where('question', $question)->delete();

            return View::make('company.dashboard.saveQuestionsAjax')->with('questions', QuestionsModel::where('company_id', $companyId)->orWhere('by_admin', 1)->get());
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncSaveQuestionnaire() {
        if (Session::has('agency_id')) {
            $companyId = Input::get('agency_id');
            $title = Input::get('title');
            $questionnaireId = Input::get('questionnaire_id');
            $questions = explode(",", Input::get('questions'));

            $questionnaire = new QuestionnairesModel;

            if ($questionnaireId != '') {
                $questionnaire = QuestionnairesModel::find($questionnaireId);
            }

            $questionnaire->title = $title;
            $questionnaire->company_id = $companyId;

            $questionnaire->save();

            QuestionnaireQuestionsModel::where('questionnaires_id', $questionnaire->id)->delete();

            foreach ($questions as $questionId) {

                $questionnareQuesions = new QuestionnaireQuestionsModel;

                $questionnareQuesions->questionnaires_id = $questionnaire->id;
                $questionnareQuesions->questions_id = $questionId;

                $questionnareQuesions->save();
            }

            $questionnaires = QuestionnairesModel::all();
            $tableData = array();

            foreach($questionnaires as $qaKey => $qaValue) {
                $tableData[$qaKey] = array();

                $questions = '';
                $questionIds = '';

                foreach ($qaValue->questions as $qnKey => $qnValue) {
                    $questions .= '<tr><td>'. ($qnKey + 1) .'.</td><td>'. $qnValue->questions->question .'</td></tr>';
                    if (strlen($questionIds) != 0) $questionIds .= ',';
                    $questionIds .= $qnValue->questions_id;
                }

                $tableData[$qaKey]['id'] = $qaValue->id;
                $tableData[$qaKey]['title'] = $qaValue->title;
                $tableData[$qaKey]['questions'] = $questions;
                $tableData[$qaKey]['questionIds'] = $questionIds;
            }



            return Response::json(['result' => 'success', 'table' => View::make('company.dashboard.saveQuestionnaireAjax')->with('questionnaires', QuestionnairesModel::where('company_id', $companyId)->orWhere('by_admin', 1)->get())->__toString(), 'questionnaires' => $tableData]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public  function asyncDeleteQuestionnaire() {
        if (Session::has('agency_id')) {
            $questionnairesId = Input::get('questionnaires_id');

            QuestionnairesModel::where('id', $questionnairesId)->delete();

            $questionnaires = QuestionnairesModel::all();
            $tableData = array();

            foreach($questionnaires as $qaKey => $qaValue) {
                $tableData[$qaKey] = array();

                $questions = '';
                $questionIds = '';

                foreach ($qaValue->questions as $qnKey => $qnValue) {
                    $questions .= '<tr><td>'. ($qnKey + 1) .'.</td><td>'. $qnValue->questions->question .'</td></tr>';
                    if (strlen($questionIds) != 0) $questionIds .= ',';
                    $questionIds .= $qnValue->questions_id;
                }

                $tableData[$qaKey]['id'] = $qaValue->id;
                $tableData[$qaKey]['title'] = $qaValue->title;
                $tableData[$qaKey]['questions'] = $questions;
                $tableData[$qaKey]['questionIds'] = $questionIds;
            }

            return Response::json(['result' => 'success', 'table' => View::make('company.dashboard.saveQuestionnaireAjax')->with('questionnaires', QuestionnairesModel::where('company_id', Session::get('agency_id'))->orWhere('by_admin', 1)->get())->__toString(), 'questionnaires' => $tableData]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncSaveVITemplate() {
        if (Session::has('agency_id')) {
            $companyId = Input::get('agency_id');
            $title = Input::get('title');
            $description = Input::get('description');
            $templateId = Input::get('template_id');

            $viTemplate = new CompanyVITemplateModel;

            if ($templateId != '') {
                $viTemplate = CompanyVITemplateModel::find($templateId);
            }

            $viTemplate->title = $title;
            $viTemplate->description = $description;
            $viTemplate->company_id = $companyId;

            $viTemplate->save();



            $templates = CompanyVITemplateModel::where('company_id', $companyId)->orWhere('by_admin', 1)->get();
            $tableData = array();

            foreach($templates as $qaKey => $qaValue) {
                $tableData[$qaKey] = array();

                $tableData[$qaKey]['id'] = $qaValue->id;
                $tableData[$qaKey]['title'] = $qaValue->title;
                $tableData[$qaKey]['description'] = $qaValue->description;
            }



            return Response::json(['result' => 'success', 'table' => View::make('company.dashboard.saveVITemplateAjax')->with('templates', $templates)->__toString(), 'questionnaires' => $tableData]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncDeleteVITemplate() {
        if (Session::has('agency_id')) {
            $companyId = Session::get('agency_id');
            $templateId = Input::get('template_id');

            CompanyVITemplateModel::where('id', $templateId)->delete();

            $templates = CompanyVITemplateModel::where('company_id', $companyId)->orWhere('by_admin', 1)->get();
            $tableData = array();

            foreach($templates as $qaKey => $qaValue) {
                $tableData[$qaKey] = array();

                $tableData[$qaKey]['id'] = $qaValue->id;
                $tableData[$qaKey]['title'] = $qaValue->title;
                $tableData[$qaKey]['description'] = $qaValue->description;
            }



            return Response::json(['result' => 'success', 'table' => View::make('company.dashboard.saveVITemplateAjax')->with('templates', $templates)->__toString(), 'questionnaires' => $tableData]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }



    public function asyncSaveApplyNote() {

        if (Session::has('agency_id')) {
            $applyId = Input::get('apply_id');
            $notes = Input::get('notes');
            $companyId = Session::get('agency_id');

            $count = CompanyApplyNoteModel::where('company_id', $companyId)->where('apply_id', $applyId)->get()->count();

            if ($count > 0) {
                $note = CompanyApplyNoteModel::where('company_id', $companyId)->where('apply_id', $applyId)->firstOrFail();
            }else {
                $note = new CompanyApplyNoteModel;
            }

            $note->company_id = $companyId;
            $note->apply_id = $applyId;
            $note->notes = $notes;

            $note->save();

            return Response::json(['result' => 'success', 'msg' => 'Notes has been saved successfully.']);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncSendMessage() {
        $applyId = Input::get('apply_id');

        $apply = CompanyApplyModel::find($applyId);

        $userId = $apply->user->id;
        $message_data = Input::get('message');

        $user = UserModel::find($userId);
        $company = CompanyModel::find(Session::get('agency_id'));

        Queue::push('\SH\Queue\CompanyUserApplyMessage', ['user_id' => $user->id, 'company_id' => $company->id, 'message_data' => $message_data] );

        $message = new MessageModel;
        $message->user_id = $userId;
        $message->company_id = Session::get('company_id');
        $message->description = $message_data;
        $message->is_company_sent = TRUE;
        $message->is_read = FALSE;
        $message->save();

        return Response::json(['result' => 'success', 'msg' => 'Message has been sent successfully.']);
    }


    public function asyncViewInterview() {

        if (Session::has('agency_id')) {
            $interviewId = Input::get('interview_id');
            $param['interview'] = CompanyVICreatedModel::find($interviewId);
            $param['agency'] = CompanyModel::find(Session::get('agency_id'));

            return Response::json(['result' => 'success', 'interviewView' => View::make('agency.dashboard.ajaxViewInterview')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }
}
