<?php namespace Agency;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL;

use Company as CompanyModel;
use Message as MessageModel;

class MessageController extends \BaseController {
	
	public function index() {
	    $company = CompanyModel::find(Session::get('agency_id'));
	    $param['pageNo'] = 7;
	    $param['agency'] = $company;
	    $param['messages'] = MessageModel::whereIn('company_id', $company->companyIds())->groupBy('user_id', 'job_id')->get();
	    if ($alert = Session::get('alert')) {
	        $param['alert'] = $alert;
	    }
	    return View::make('agency.message.index')->with($param);
	}
	
	public function detail($companySlug, $userId, $jobId = NULL) {
	    $company = CompanyModel::findBySlug($companySlug);
	    $param['agency'] = $company;
	    $param['pageNo'] = 7;
	    $param['jobId'] = $jobId;
	    $param['userId'] = $userId;
	    
	    if (!Session::has('agency_id')) {
	        Session::set('agency_is_admin', $company->is_admin);
	        Session::set('agency_id', $company->id);
	    }
	    
	    $messages = $company->newMessages($jobId, $userId)->get();
	    
	    foreach ($messages as $message) {
	        $message->is_read = TRUE;
	        $message->save();
	    }
	    
	    $messages = MessageModel::whereIn('company_id', $company->companyIds())
                        ->where('user_id', $userId)
                        ->where('job_id', $jobId)
                        ->orderBy('created_at', 'DESC')
                        ->get();

	    $param['messages'] = $messages;
	    
	    return View::make('agency.message.detail')->with($param);
	}
}
