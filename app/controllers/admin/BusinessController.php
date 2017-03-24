<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Business as BusinessModel;

class BusinessController extends \BaseController {

	public function index() {
		$param['businesses'] = BusinessModel::paginate(10);
        $param['pageNo'] = 4;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.business.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 4;
		
	    return View::make('admin.business.create')->with($param);
	}
	
	public function edit($id) {
	    $param['business'] = BusinessModel::find($id);
	    $param['pageNo'] = 4;
	    
	    return View::make('admin.business.edit')->with($param);
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
	        
	        if (Input::has('business_id')) {
	            $id = Input::get('business_id');
	            $business = BusinessModel::find($id);
	            
	            $alert['msg'] = 'Business has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
	            $business = new BusinessModel;	     

	            $alert['msg'] = 'Business has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       		$is_approved = Input::get('is_approved');
       		
	        $business->name = $name;
	        $business->is_approved = $is_approved;
	        $business->save();
	          
	        return Redirect::route('admin.business')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {
	    BusinessModel::find($id)->delete();
	    
	    $alert['msg'] = 'Business has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.business')->with('alert', $alert);
	}
}
