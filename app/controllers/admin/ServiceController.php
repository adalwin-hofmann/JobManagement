<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Service as ServiceModel;

class ServiceController extends \BaseController {
    
	public function index() {
        $param['services'] = ServiceModel::paginate(10);
        $param['pageNo'] = 3;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.service.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 3;
		
	    return View::make('admin.service.create')->with($param);
	}
	
	public function edit($id) {
	    $param['service'] = ServiceModel::find($id);
	    $param['pageNo'] = 3;
	    
	    return View::make('admin.service.edit')->with($param);
	}
	
	public function store() {
	    
	    $rules = ['name'      => 'required', 
	    		  'icon_code' => 'required|numeric'];
	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
	        $name = Input::get('name');
	        $iconCode = Input::get('icon_code');
	        
	        if (Input::has('service_id')) {
	            $id = Input::get('service_id');
	            $service = ServiceModel::find($id);
	            
	            $alert['msg'] = 'Service has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
	            $service = new ServiceModel;	     

	            $alert['msg'] = 'Service has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       
	        
	        $service->name = $name;
	        $service->icon_code = $iconCode;
	        
	        $service->save();
	          
	        return Redirect::route('admin.service')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {

	    
	    try {
		    ServiceModel::find($id)->delete();
		    
		    $alert['msg'] = 'Service has been deleted successfully';
		    $alert['type'] = 'success';
	    } catch(\Exception $ex) {
	    	$alert['msg'] = 'This service has been already used';
	    	$alert['type'] = 'danger';
	    }
	    
	    return Redirect::route('admin.service')->with('alert', $alert);
	}
}
