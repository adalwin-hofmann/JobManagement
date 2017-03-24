<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Company as CompanyModel;
use CompanySetting as CompanySettingModel;

class SettingController extends \BaseController {
        
    public function index() {
        $param['pageNo'] = 13;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $param['company'] = CompanyModel::find(Session::get('company_id'));
        return View::make('company.setting.index')->with($param);
    }
    
    public function store() {
        $company = CompanyModel::find(Session::get('company_id'));
        if ($company->setting) {
            $companySetting = $company->setting;
        } else {
            $companySetting = new CompanySettingModel;
        }
        $companySetting->company_id = Session::get('company_id');
        $companySetting->slot_background = Input::get('slot_background');
        $companySetting->start_at = Input::get('start_at');
        $companySetting->end_at = Input::get('end_at');
        $companySetting->save();
        
        $alert['msg'] = 'Setting has been saved successfully';
        $alert['type'] = 'success';
        return Redirect::route('company.setting')->with('alert', $alert);        
    }
}
