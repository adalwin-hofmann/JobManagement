<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Level as LevelModel;

class LevelController extends \BaseController {
    
	public function index() {
        $param['levels'] = LevelModel::paginate(10);
        $param['pageNo'] = 7;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.level.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 7;
		
	    return View::make('admin.level.create')->with($param);
	}
	
	public function edit($id) {
	    $param['level'] = LevelModel::find($id);
	    $param['pageNo'] = 7;
	    
	    return View::make('admin.level.edit')->with($param);
	}
	
	public function store() {
	    
	    $rules = ['name' => 'required'];
	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
			$name = Input::get('name');
	        
	        if (Input::has('level_id')) {
	            $id = Input::get('level_id');
	            $level = LevelModel::find($id);
	            
	            $alert['msg'] = 'Level has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
	            $level = new LevelModel;	     

	            $alert['msg'] = 'Level has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       
	        
	        $level->name = $name;
	        
	        $level->save();
	          
	        return Redirect::route('admin.level')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {
	    LevelModel::find($id)->delete();
	    
	    $alert['msg'] = 'Level has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.level')->with('alert', $alert);
	}
}
