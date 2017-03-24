<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Language as LanguageModel;

class LanguageController extends \BaseController {
    
	public function index() {
        $param['languages'] = LanguageModel::paginate(10);
        $param['pageNo'] = 5;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.language.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 5;
		
	    return View::make('admin.language.create')->with($param);
	}
	
	public function edit($id) {
	    $param['language'] = LanguageModel::find($id);
	    $param['pageNo'] = 5;
	    
	    return View::make('admin.language.edit')->with($param);
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
	        
	        if (Input::has('language_id')) {
	            $id = Input::get('language_id');
	            $language = LanguageModel::find($id);
	            
	            $alert['msg'] = 'Language has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
	            $language = new LanguageModel;	     

	            $alert['msg'] = 'Language has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       
	        
	        $language->name = $name;
	        
	        $language->save();
	          
	        return Redirect::route('admin.language')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {
	    LanguageModel::find($id)->delete();
	    
	    $alert['msg'] = 'Language has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.language')->with('alert', $alert);
	}
}
