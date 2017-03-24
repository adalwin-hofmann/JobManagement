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
use AgencyClient as AgencyClientModel;

class CompanyController extends \BaseController {
	
	public function index() {
		
		if (!Session::has('agency_id')) {
			return Redirect::route('agency.auth.login');
		}else {
			$param['pageNo'] = 10;
            $param['companies'] = CompanyModel::where('is_spam', 0)->where('is_active', 1)->where('is_agency', 0)->where('is_admin', 1)->orderBy('created_at', 'Desc')->get();
            $param['agency'] = CompanyModel::find(Session::get('agency_id'));
			
			return View::make('agency.company.index')->with($param);
		}
	}


    public function asyncSetClient() {
        if (Session::has('agency_id')) {

            $companyId = Input::get('company_id');

            $client = new AgencyClientModel;

            $client->agency_id = Session::get('agency_id');
            $client->company_id = $companyId;
            $client->save();


            $param['companies'] = CompanyModel::where('is_spam', 0)->where('is_active', 1)->where('is_agency', 0)->where('is_admin', 1)->orderBy('created_at', 'Desc')->get();
            $param['agency'] = CompanyModel::find(Session::get('agency_id'));

            return Response::json(['result' => 'success', 'table' => View::make('agency.company.ajaxCompanyList')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncRemoveClient() {
        if (Session::has('agency_id')) {

            $companyId = Input::get('company_id');

            AgencyClientModel::where('agency_id', Session::get('agency_id'))->where('company_id', $companyId)->delete();

            $param['companies'] = CompanyModel::where('is_spam', 0)->where('is_active', 1)->where('is_agency', 0)->where('is_admin', 1)->orderBy('created_at', 'Desc')->get();
            $param['agency'] = CompanyModel::find(Session::get('agency_id'));

            return Response::json(['result' => 'success', 'table' => View::make('agency.company.ajaxCompanyList')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncAdd() {
        if (Session::has('agency_id')) {

            $name = Input::get('name');
            $email = Input::get('email');

            if (CompanyModel::where('email', $email)->get()->count() > 0) {
                return Response::json(['result' => 'fail', 'msg' => 'Email is already registered.']);
            }

            $company = new CompanyModel;

            $company->salt = str_random(8);
            $company->secure_key = md5($company->salt.$company->salt);
            $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
            $company->category_id = CategoryModel::whereRaw(true)->min('id');
            $company->city_id = CityModel::whereRaw(true)->min('id');
            $company->name = $name;
            $company->email = $email;
            $company->logo = 'default_company_logo.gif';
            $company->is_admin = 1;
            $company->is_finished 	= 0;
            $company->is_active = 1;
            $company->overlay_color = 'rgba(0, 82, 208, 0.9)';

            $company->save();

            $company->parent_id = $company->id;
            $company->save();

            $param['companies'] = CompanyModel::where('is_spam', 0)->where('is_active', 1)->where('is_agency', 0)->where('is_admin', 1)->orderBy('created_at', 'Desc')->get();
            $param['agency'] = CompanyModel::find(Session::get('agency_id'));

            return Response::json(['result' => 'success', 'table' => View::make('agency.company.ajaxCompanyList')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

}
