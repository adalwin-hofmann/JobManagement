<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Teamsize as TeamsizeModel;

class TeamsizeController extends \BaseController {
    
	public function index() {
        $param['teamsizes'] = TeamsizeModel::paginate(10);
        $param['pageNo'] = 4;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.teamsize.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 4;
		
	    return View::make('admin.teamsize.create')->with($param);
	}
	
	public function edit($id) {
	    $param['teamsize'] = TeamsizeModel::find($id);
	    $param['pageNo'] = 4;
	    
	    return View::make('admin.teamsize.edit')->with($param);
	}
	
	public function store() {
	    
	    $rules = ['min' => 'required|numeric', 
	    		  'max' => 'required|numeric',];
	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
	        $min = Input::get('min');
	        $max = Input::get('max');
	        
	        if (Input::has('teamsize_id')) {
	            $id = Input::get('teamsize_id');
	            $teamsize = TeamsizeModel::find($id);
	            
	            $alert['msg'] = 'Teamsize has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
	            $teamsize = new TeamsizeModel;	     

	            $alert['msg'] = 'Teamsize has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       
	        
	        $teamsize->min = $min;
	        $teamsize->max = $max;
	        
	        $teamsize->save();
	          
	        return Redirect::route('admin.teamsize')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {
	    
	    
	    try {
	    	TeamsizeModel::find($id)->delete();
	    	 
		    $alert['msg'] = 'Teamsize has been deleted successfully';
		    $alert['type'] = 'success';
	    } catch(\Exception $ex) {
	    	$alert['msg'] = 'This teamsize has been already used';
	    	$alert['type'] = 'danger';
	    }
	    

	    
	    return Redirect::route('admin.teamsize')->with('alert', $alert);
	}
}
