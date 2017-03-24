<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL, Queue;

use Company as CompanyModel;
use City as CityModel;
use Category as CategoryModel;
use Teamsize as TeamsizeModel;
use Service as ServiceModel;
use CompanyService as CompanyServiceModel;
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
use CompanyApply as CompanyApplyModel;
use CompanyApplyNote as CompanyApplyNoteModel;
use CompanyVICreated as CompanyVICreatedModel;
use Message as MessageModel;
use User as UserModel;

class CompanyController extends \BaseController {
    public function index() {
        if (!Session::has('company_id')) {
            return Redirect::route('company.auth.login');
        } else {
            $param['pageNo'] = 3;    

            $startDate = Input::has('startDate') ? Input::get('startDate') : date("Y-m-d", strtotime("-3 Months"));
            $endDate = Input::has('endDate') ? Input::get('endDate') : date("Y-m-d");
            $period = Input::has('period') ? Input::get('period') : 90;
            
            if (Input::has('period')) {
                $startDate = date('Y-m-d', strtotime('-'.$period.' days', strtotime($endDate)));
            }
            
            $company = CompanyModel::find(Session::get('company_id'));

            if ($company->is_admin == 1) {
                $parentId = $company->id;
            } else {
                $parentId = $company->parentId;
            }

            $param['members'] = CompanyModel::whereIn('id', $company->companyIds())->get();
            
            $param['startDate'] = $startDate;
            $param['endDate'] = $endDate;
            $param['period'] = $period;
            $param['company'] = $company;

            $param['questionnaires'] = QuestionnairesModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
            $param['viTemplates'] = CompanyVITemplateModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();

            return View::make('company.dashboard.home')->with($param);
        }
    }
    
    public function addJob() {
        
        if (!Session::has('company_id')) {
            return Redirect::route('company.auth.login');
        } else {
            $param['pageNo'] = 1;
            
            $param['company_id'] = Session::get('company_id');
            $param['companies'] = CompanyModel::all();
            $param['categories'] = CategoryModel::all();
            $param['presences'] = PresenceModel::all();
            $param['cities'] = CityModel::all();
            $param['languages'] = LanguageModel::all();
            $param['types'] = TypeModel::all();
            $param['levels'] = LevelModel::all();
            
            if ($alert = Session::get('alert')) {
                $param['alert'] = $alert;
            }
            return View::make('company.job.add')->with($param);            
        }
    }
    
    public function doAddJob() {
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
            $is_name = Input::has('is_name') ? Input::get('is_name') : 0;
            $is_phonenumber = Input::has('is_phonenumber') ? Input::get('is_phonenumber') : 0;
            $is_email = Input::has('is_email') ? Input::get('is_email') : 0;
            $is_currentjob = Input::has('is_currentjob') ? Input::get('is_currentjob') : 0;
            $is_previousjobs = Input::has('is_previousjobs') ? Input::get('is_previousjobs') : 0;
            $is_description = Input::has('is_description') ? Input::get('is_description') : 0;
        
            $job = new JobModel;
            $job->company_id = Input::get('company_id');
            $job->name = Input::get('name');
            $job->level_id = Input::get('level_id');
            $job->description = Input::get('description');
            $job->category_id = Input::get('category_id');
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
            $alert['msg'] = 'Job has been saved successfully';
            $alert['type'] = 'success';
            return Redirect::route('company.job.add')->with('alert', $alert);
        }
    }
    
    public function myjobs($id = 0) {
        
        if (!Session::has('company_id')) {
            return Redirect::route('company.auth.login');
        } else {
            $param['pageNo'] = 2;
            if ($alert = Session::get('alert')) {
                $param['alert'] = $alert;
            }
            
            if ($id == 0) {
                $param['jobs'] = JobModel::where('company_id', Session::get('company_id'))->paginate(PAGINATION_SIZE);
            } else {
                $param['jobs'] = JobModel::where('company_id', Session::get('company_id'))->where('category_id', $id)->paginate(PAGINATION_SIZE);
            }
            $param['category'] = $id;
            $param['categories'] = CategoryModel::all();
            
            return View::make('company.job.myjobs')->with($param);            
        }    
    }
    
    public function profile() {
        if (!Session::has('company_id')) {
            return Redirect::route('company.auth.login');
        } else {
            $param['pageNo'] = 4;
            $param['company'] = CompanyModel::find(Session::get('company_id'));
            $param['cities'] = CityModel::all();
            $param['teamsizes'] = TeamsizeModel::all();
            $param['categories'] = CategoryModel::all();
            $param['services']  = ServiceModel::all();
            $param['companyServices']  = CompanyServiceModel::where('company_id', Session::get('company_id'))->get();
            $param['questions'] = QuestionsModel::where('company_id', Session::get('company_id'))->orWhere('by_admin', 1)->get();
            $param['questionnaires'] = QuestionnairesModel::where('company_id', Session::get('company_id'))->orWhere('by_admin', 1)->get();
            $param['templates'] = CompanyVITemplateModel::where('company_id', Session::get('company_id'))->get();
            
            if ($alert = Session::get('alert')) {
                $param['alert'] = $alert;
            }
            
            return View::make('company.dashboard.profile')->with($param);            
        }
    }
    
    public function saveProfile() {

        $rules = ['name'         => 'required',
                  'email'         => 'required|email',
                  'year'         => 'numeric',
                  'service_id'    => 'required',
                  ];
        
        $validator = Validator::make(Input::all(), $rules);
         
        if ($validator->fails()) {
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $password = Input::get('password');
            if (Input::has('company_id')) {
                $company = CompanyModel::find(Input::get('company_id'));
                $is_active = Input::get('is_active');
        
                if ($password !== '') {
                    $company->secure_key = md5($company->salt.$password);
                }
                $company->is_active = 1;
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
        
            $company->name             = Input::get('name');
            $company->tag              = Input::get('tag');
            $company->year             = Input::get('year');
            $company->teamsize_id      = Input::get('teamsize_id');
            $company->category_id      = Input::get('category_id');
            $company->city_id          = Input::get('city_id');
            $company->description      = Input::get('description');
            $company->expertise        = Input::get('expertise');
            $company->address          = Input::get('address');
            $company->email            = Input::get('email');
            $company->phone            = Input::get('phone');
            $company->website          = Input::get('website');
            $company->facebook         = Input::get('facebook');
            $company->linkedin         = Input::get('linkedin');
            $company->twitter          = Input::get('twitter');
            $company->google           = Input::get('google');
            $company->lat              = Input::get('lat');
            $company->long              = Input::get('lng');
            $company->apply_rejection_title = Input::get('apply_rejection_title');
            $company->apply_rejection_content = Input::get('apply_rejection_content');
            $company->hint_rejection_title = Input::get('hint_rejection_title');
            $company->hint_rejection_content = Input::get('hint_rejection_content');
            $company->is_published  = Input::has('is_published') ? Input::get('is_published') : 0;
            $company->is_finished     = 1;
            $company->is_show       = Input::has('is_show') ? Input::get('is_show') : 0;
            $company->hide_bids_iframe  = Input::has('hide_bids_iframe') ? Input::get('hide_bids_iframe') : 0;
            $company->hide_bonus_iframe = Input::has('hide_bonus_iframe') ? Input::get('hide_bonus_iframe') : 0;
            $company->hide_salary_iframe= Input::has('hide_salary_iframe') ? Input::get('hide_salary_iframe') : 0;
            $company->overlay_color     = Input::get('overlay_color');
            $company->video_interview_text = Input::get('video-interview-text');
            $company->video_interview_end = Input::get('video-interview-end');

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

            $company->save();
        
            $companyId = $company->id;
        
            CompanyServiceModel::where('company_id', $companyId)->delete();
            if (Input::has('service_id')) {
                $count = 0;
                foreach (Input::get('service_id') as $sId) {
                    if ($sId == '') break;
                    $companyService = new CompanyServiceModel;
                    $companyService->company_id = $companyId;
                    $companyService->service_id = $sId;
                    $companyService->description = Input::get('service_description')[$count];
                    $companyService->save();
                    $count ++;
                }
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
        
            return Redirect::route('company.profile')->with('alert', $alert);
        }
    }


    // ajax functions
    public function asyncSaveQuestion(){
        if (Session::has('company_id')) {
            $companyId = Input::get('company_id');
            $question = Input::get('question');
            $time = Input::get('time');

            $questions = new QuestionsModel;

            if (QuestionsModel::where('company_id', $companyId)->where('question', $question)->get()->count() > 0) {
                $questions = QuestionsModel::where('company_id', $companyId)
                                           ->where('question', $question)
                                           ->firstOrFail();
            }
            
            $questions->company_id = $companyId;
            $questions->question = $question;
            $questions->by_admin = 0;
            $questions->time = $time;
            $questions->save();
            return View::make('company.dashboard.saveQuestionsAjax')->with('questions', QuestionsModel::where('company_id', $companyId)
                                                                                                      ->orWhere('by_admin', 1)
                                                                                                      ->get());
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncDeleteQuestion(){
        if (Session::has('company_id')) {
            $companyId = Input::get('company_id');
            $question = Input::get('question');
            
            QuestionsModel::where('company_id', $companyId)
                          ->where('question', $question)
                          ->delete();

            return View::make('company.dashboard.saveQuestionsAjax')->with('questions', QuestionsModel::where('company_id', $companyId)
                                                                                                      ->orWhere('by_admin', 1)
                                                                                                      ->get());
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncSaveQuestionnaire() {
        if (Session::has('company_id')) {
            $companyId = Input::get('company_id');
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
            
            $tableData = [];
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
            return Response::json(['result' => 'success', 'questionnaires' => $tableData, 
                                   'table' => View::make('company.dashboard.saveQuestionnaireAjax')
                                                  ->with('questionnaires', QuestionnairesModel::where('company_id', $companyId)
                                                  ->orWhere('by_admin', 1)
                                                  ->get())->__toString(), 
                                   ]);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public  function asyncDeleteQuestionnaire() {
        if (Session::has('company_id')) {
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
            return Response::json(['result' => 'success', 'questionnaires' => $tableData,  
                                   'table' => View::make('company.dashboard.saveQuestionnaireAjax')->with('questionnaires', QuestionnairesModel::where('company_id', Session::get('company_id'))->orWhere('by_admin', 1)->get())->__toString()]);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncSaveVITemplate() {
        if (Session::has('company_id')) {
            $companyId = Input::get('company_id');
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
            return Response::json(['result' => 'success', 'questionnaires' => $tableData, 
                                   'table' => View::make('company.dashboard.saveVITemplateAjax')->with('templates', $templates)->__toString()]);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncDeleteVITemplate() {
        if (Session::has('company_id')) {
            $companyId = Session::get('company_id');
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
            return Response::json(['result' => 'success', 'questionnaires' => $tableData, 
                                   'table' => View::make('company.dashboard.saveVITemplateAjax')->with('templates', $templates)->__toString()]);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncSaveApplyNote() {

        if (Session::has('company_id')) {
            $applyId = Input::get('apply_id');
            $notes = Input::get('notes');
            $companyId = Session::get('company_id');
            $count = CompanyApplyNoteModel::where('company_id', $companyId)->where('apply_id', $applyId)->get()->count();
            if ($count > 0) {
                $note = CompanyApplyNoteModel::where('company_id', $companyId)->where('apply_id', $applyId)->firstOrFail();
            } else {
                $note = new CompanyApplyNoteModel;
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


    public function asyncSendMessage() {
        $applyId = Input::get('apply_id');
        $apply = CompanyApplyModel::find($applyId);

        $userId = $apply->user->id;
        $message_data = Input::get('message');

        $user = UserModel::find($userId);
        $company = CompanyModel::find(Session::get('company_id'));

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
        if (Session::has('company_id')) {
            $interviewId = Input::get('interview_id');
            $param['interview'] = CompanyVICreatedModel::find($interviewId);
            $param['company'] = CompanyModel::find(Session::get('company_id'));
            return Response::json(['result' => 'success', 'interviewView' => View::make('company.dashboard.ajaxViewInterview')->with($param)->__toString()]);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }
}
