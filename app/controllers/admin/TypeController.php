<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Type as TypeModel;

class TypeController extends \BaseController {
    
	public function index() {
        $param['types'] = TypeModel::paginate(10);
        $param['pageNo'] = 8;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.type.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 8;
		
	    return View::make('admin.type.create')->with($param);
	}
	
	public function edit($id) {
	    $param['type'] = TypeModel::find($id);
	    $param['pageNo'] = 8;
	    
	    return View::make('admin.type.edit')->with($param);
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
	        
	        if (Input::has('type_id')) {
	            $id = Input::get('type_id');
	            $type = TypeModel::find($id);
	            
	            $alert['msg'] = 'Type has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
	            $type = new TypeModel;	     

	            $alert['msg'] = 'Type has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       
	        
	        $type->name = $name;
	        
	        $type->save();
	          
	        return Redirect::route('admin.type')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {
	    TypeModel::find($id)->delete();
	    
	    $alert['msg'] = 'Type has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.type')->with('alert', $alert);
	}
}
