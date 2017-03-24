<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL, Queue;

use Company as CompanyModel;
use AgencyShare as AgencyShareModel;
use Questionnaires as QuestionnairesModel;
use CompanyVITemplate as CompanyVITemplateModel;

class ShareController extends \BaseController {

    public function index() {
        if (!Session::has('company_id')) {
            return Redirect::route('company.auth.login');
        }else {

            $company = CompanyModel::find(Session::get('company_id'));

            $param['company'] = $company;
            $param['pageNo'] = 9;
            $param['questionnaires'] = QuestionnairesModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
            $param['viTemplates'] = CompanyVITemplateModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();

            return View::make('company.share.index')->with($param);
        }
    }

    public function linkToIndex($cSlug) {

        if (!Input::has('_token')) {
            return View::make('404.index');
        }

        $company = CompanyModel::findBySlug($cSlug);

        if ($company->salt != Input::get('_token')) {
            return View::make('404.index');
        }

        if (!Session::has('company_id')) {
            Session::set('company_is_admin', $company->is_admin);
            Session::set('company_id', $company->id);
        }

        return Redirect::route('company.share');
    }

    public function viewOnApp($cSlug, $shareId) {

        $company = CompanyModel::findBySlug($cSlug);
        $agencyShare = AgencyShareModel::find($shareId);

        $companyLogo = $agencyShare->agency->agency_sharing_logo;
        if ($companyLogo == '') {
            $companyLogo = 'default_company_logo.png';
        }

        $companyBackground = $agencyShare->agency->agency_sharing_background;
        if ($companyBackground == '') {
            $companyBackground = 'default.png';
        }


        if ($company->id != $agencyShare->company->id) {
            return View::make('404.index');
        }

        if (!Session::has('company_id')) {
            Session::set('company_is_admin', $company->is_admin);
            Session::set('company_id', $company->id);
        }

        $param['share'] = $agencyShare;
        $param['company'] = $company;
        $param['agency'] = CompanyModel::find($agencyShare->agency_id);
        $param['companyLogo'] = $companyLogo;
        $param['companyBackground'] = $companyBackground;

        $param['questionnaires'] = QuestionnairesModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();
        $param['viTemplates'] = CompanyVITemplateModel::where('company_id', $company->id)->orWhere('by_admin', 1)->get();

        return View::make('company.share.viewOnApp')->with($param);
    }
}
