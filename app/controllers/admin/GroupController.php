<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Mail, Queue;
use Group as GroupModel;
use Company as CompanyModel;
use GroupCompany as GroupCompanyModel;
use GroupMarketing as GroupMarketingModel;

class GroupController extends \BaseController {
        
    public function index() {
        $param['pageNo'] = 16;
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $param['groups'] = GroupModel::paginate(PAGINATION_SIZE);
        return View::make('admin.group.index')->with($param);
    }
    
    public function create() {
        $param['pageNo'] = 16;
        return View::make('admin.group.create')->with($param);
    }
    
    public function edit($id) {
        $param['pageNo'] = 16;
        $group = GroupModel::find($id);
        $param['group'] = $group;
        
        $companyIds = [];
        $companyIds[] = 0;
        foreach ($group->groupCompanies as $groupCompany) {
            $companyIds[] = $groupCompany->company_id;
        }
        
        $param['excludeCompanies'] = CompanyModel::whereNotIn('id', $companyIds)
                                                 ->where('is_admin', 1)
                                                 ->where('is_finished', 1)
                                                 ->paginate(PAGINATION_SIZE);
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }        
        
        return View::make('admin.group.edit')->with($param);
    }
    
    public function store() {
        
        $rules = ['name' => 'required'];
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            if (Input::has('group_id')) {
                $id = Input::get('group_id');
                $group = GroupModel::find($id);
            } else {
                $group = new GroupModel;                
            }
            $group->name = Input::get('name');
            $group->description = Input::get('description');
            $group->save();
            
            $alert['msg'] = 'Group has been saved successfully';
            $alert['type'] = 'success';            
              
            return Redirect::route('admin.group')->with('alert', $alert);            
        }
    }
    

    public function includeCompany($groupId, $companyId) {
        $groupCompany = new GroupCompanyModel;
        $groupCompany->group_id = $groupId;
        $groupCompany->company_id = $companyId;
        $groupCompany->save();
        
        $alert['msg'] = 'Company has been added on this group';
        $alert['type'] = 'success';        
        
        return Redirect::route('admin.group.edit', $groupId)->with('alert', $alert);
    }
    
    public function excludeCompany($groupId, $companyId) {
        $groupCompany = GroupCompanyModel::where('group_id', $groupId)
                                         ->where('company_id', $companyId)
                                         ->delete();
        
        $alert['msg'] = 'Company has been removed from this group';
        $alert['type'] = 'danger';
    
        return Redirect::route('admin.group.edit', $groupId)->with('alert', $alert);
    }
    
    public function delete($id) {
        try {
            GroupModel::find($id)->delete();
            
            $alert['msg'] = 'Group has been deleted successfully';
            $alert['type'] = 'success';
        } catch(\Exception $ex) {
            $alert['msg'] = 'This Group has been already used';
            $alert['type'] = 'danger';
        }

        return Redirect::route('admin.group')->with('alert', $alert);
    }
    
    public function marketing($id) {
        $param['pageNo'] = 16;
        $param['group'] = GroupModel::find($id);
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }        
        return View::make('admin.group.marketing')->with($param);
    }
    
    public function doMarketing() {
        
        $groupMarketing = new GroupMarketingModel;
        $groupMarketing->group_id = Input::get('group_id');
        $groupMarketing->subject = Input::get('subject');
        $groupMarketing->name = Input::get('name');
        $groupMarketing->body = Input::get('body');
        $groupMarketing->reply_name = Input::get('reply_name');
        $groupMarketing->reply_email = Input::get('reply_email');
        $groupMarketing->save();
        
        Queue::push('\SH\Queue\CompanySendMarketingMessage', ['group_marketing_id' => $groupMarketing->id] );
        
        $alert['msg'] = 'Marketing Email has been sent successfully.';
        $alert['type'] = 'success';
        return Redirect::route('admin.group')->with('alert', $alert);        
    }
}
