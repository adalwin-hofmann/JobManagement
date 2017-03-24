<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Country as CountryModel;

class CountryController extends \BaseController {
    
	public function index() {
        $param['countries'] = CountryModel::paginate(10);
        $param['pageNo'] = 1;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.country.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 1;
		
	    return View::make('admin.country.create')->with($param);
	}
	
	public function edit($id) {
	    $param['country'] = CountryModel::find($id);
	    $param['pageNo'] = 1;
	    
	    return View::make('admin.country.edit')->with($param);
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
	        
	        if (Input::has('country_id')) {
	            $id = Input::get('country_id');
	            $country = CountryModel::find($id);
	            
	            $alert['msg'] = 'Country has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
	            $country = new CountryModel;	     

	            $alert['msg'] = 'Country has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       
	        
	        $country->name = $name;
	        $country->save();
	          
	        return Redirect::route('admin.country')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {
	    CountryModel::find($id)->delete();
	    
	    $alert['msg'] = 'Country has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.country')->with('alert', $alert);
	}
}
