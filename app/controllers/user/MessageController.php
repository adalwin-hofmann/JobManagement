<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL;
use User as UserModel;
use Message as MessageModel;

class MessageController extends \BaseController {
	
	public function index() {
	    $user = UserModel::find(Session::get('user_id'));
	    $param['pageNo'] = 7;
	    $param['user'] = $user;
	    $param['messages'] = MessageModel::where('user_id', Session::get('user_id'))->groupBy('job_id')->get();
	    if ($alert = Session::get('alert')) {
	        $param['alert'] = $alert;
	    }

	    return View::make('user.message.index')->with($param);
	}
	
	public function detail($userSlug, $companyId, $jobId = NULL) {
	    $user = UserModel::findBySlug($userSlug);
	    $param['user'] = $user;
	    $param['pageNo'] = 7;
	    $param['jobId'] = $jobId;
        $param['companyId'] = $companyId;
	    
	    if (!Session::has('user_id')) {
	        Session::set('user_id', $user->id);
	    }

	    $messages = $user->newMessages($jobId, $companyId)->get();
	    
	    foreach ($messages as $message) {
	        $message->is_read = TRUE;
	        $message->save();
	    }
	    
	    $messages = MessageModel::where('user_id', Session::get('user_id'))
	                    ->where('job_id', $jobId)
                        ->where('company_id', $companyId)
	                    ->orderBy('created_at', 'DESC')
	                    ->get();
	    
	    $param['messages'] = $messages;
	    
	    return View::make('user.message.detail')->with($param);
	}
}
