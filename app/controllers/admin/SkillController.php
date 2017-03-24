<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Skill as SkillModel;

class SkillController extends \BaseController {
    
	public function index() {
        $param['skills'] = SkillModel::paginate(10);
        $param['pageNo'] = 13;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.skill.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 13;
		
	    return View::make('admin.skill.create')->with($param);
	}
	
	public function edit($id) {
	    $param['skill'] = SkillModel::find($id);
	    $param['pageNo'] = 13;
	    
	    return View::make('admin.skill.edit')->with($param);
	}
	
	public function store() {
	    
	    $rules = ['name'    => 'required'];
	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
	        $name = Input::get('name');
	        
	        if (Input::has('skill_id')) {
	            $id = Input::get('skill_id');
	            $skill = SkillModel::find($id);
	            
	            $alert['msg'] = 'Skill has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
                $skill = new SkillModel;

	            $alert['msg'] = 'Skill has been added successfully';
	            $alert['type'] = 'success';
	        }



            $skill->name = $name;
            $skill->save();

	        return Redirect::route('admin.skill')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {
	    SkillModel::find($id)->delete();
	    
	    $alert['msg'] = 'Skill has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.skill')->with('alert', $alert);
	}
}
