<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Label as LabelModel;

class LabelController extends \BaseController {
    
	public function index() {
        $param['labels'] = LabelModel::paginate(10);
        $param['pageNo'] = 17;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.label.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 17;
		
	    return View::make('admin.label.create')->with($param);
	}
	
	public function edit($id) {
	    $param['label'] = LabelModel::find($id);
	    $param['pageNo'] = 17;
	    
	    return View::make('admin.label.edit')->with($param);
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
            $color = Input::get('color');
	        
	        if (Input::has('label_id')) {
	            $id = Input::get('label_id');
	            $label = LabelModel::find($id);
	            
	            $alert['msg'] = 'Label has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
                $label = new LabelModel;

	            $alert['msg'] = 'Label has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       
	        
	        $label->name = $name;
            $label->color = $color;
            $label->save();
	          
	        return Redirect::route('admin.label')->with('alert', $alert);
	    }
	}
	
	public function delete($id) {
	    LabelModel::find($id)->delete();
	    
	    $alert['msg'] = 'Label has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.label')->with('alert', $alert);
	}
}
