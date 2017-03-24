<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator;
use Email as EmailModel;

class EmailController extends \BaseController {
    
	public function index() {
        $param['emails'] = EmailModel::orderBy('code', 'ASC')->get();
        $param['pageNo'] = 14;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.email.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 14;
	    return View::make('admin.email.create')->with($param);
	}
	
	public function edit($id) {
	    $param['email'] = EmailModel::find($id);
	    $param['pageNo'] = 14;
	    
	    return View::make('admin.email.edit')->with($param);
	}
	
	public function store() {
	    
        $rules = ['subject' => 'required', 
                  'name'    => 'required'];

	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
	        if (Input::has('email_id')) {
	            $id = Input::get('email_id');
	            $email = EmailModel::find($id);
	        } else {
	            $email = new EmailModel;
	        }

            $email->code = Input::get('code');
            $email->name = Input::get('name');
            $email->subject = Input::get('subject');
            $email->body = Input::get('body');
            $email->reply_name = Input::get('reply_name');
            $email->reply_email = Input::get('reply_email');
            $email->save();
            
            $alert['msg'] = 'Email has been updated successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('admin.email')->with('alert', $alert);           
	    }
	}
	
	public function delete($id) {
	    EmailModel::find($id)->delete();
	    
	    $alert['msg'] = 'Email has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.email')->with('alert', $alert);
	}
}
