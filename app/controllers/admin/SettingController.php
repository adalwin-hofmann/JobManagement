<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use \SH\Models\Setting as SettingModel;

class SettingController extends \BaseController {
        
    public function index() {
        $param['pageNo'] = 15;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $param['settings'] = SettingModel::get();
        return View::make('admin.setting.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 15;
        return View::make('admin.setting.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 15;
        $param['setting'] = SettingModel::find($id);
        return View::make('admin.setting.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('setting_id')) {
                $id = Input::get('setting_id');
                $setting = SettingModel::find($id);
            } else {
                $setting = new SettingModel;                
            }
            $setting->code = Input::get('code');
            $setting->name = Input::get('name');
            $setting->value = Input::get('value');
            $setting->description = Input::get('description');
            $setting->save();
            
            $alert['msg'] = 'Setting has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('admin.setting')->with('alert', $alert);            
        }
    }
    
    public function delete($id) {
        try {
            SettingModel::find($id)->delete();
            
            $alert['msg'] = 'Setting has been deleted successfully';
            $alert['type'] = 'success';
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Setting has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('admin.setting')->with('alert', $alert);
    }
}
