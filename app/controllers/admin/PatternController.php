<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Pattern as PatternModel;

class PatternController extends \BaseController {
    
	public function index() {
        $param['patterns'] = PatternModel::paginate(10);
        $param['pageNo'] = 12;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.pattern.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 12;
		
	    return View::make('admin.pattern.create')->with($param);
	}
	
	public function edit($id) {
	    $param['pattern'] = PatternModel::find($id);
	    $param['pageNo'] = 12;
	    
	    return View::make('admin.pattern.edit')->with($param);
	}
	
	public function store() {
	    
	    $rules = [
	    	'name'    => 'required',
	    	'description' => 'required'
		];
	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
	        $name = Input::get('name');
	        $description = Input::get('description');
	        
	        if (Input::has('pattern_id')) {
	            $id = Input::get('pattern_id');
	            $pattern = PatternModel::find($id);
	            
	            $alert['msg'] = 'Pattern has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
	            $pattern = new PatternModel;	     

	            $alert['msg'] = 'Pattern has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       
	        
	        $pattern->name = $name;
	        $pattern->description = $description;
	        $pattern->save();
	          
	        return Redirect::route('admin.pattern')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {
	    PatternModel::find($id)->delete();
	    
	    $alert['msg'] = 'Pattern has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.pattern')->with('alert', $alert);
	}
}
