<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL;

use Company as CompanyModel;
use Message as MessageModel;

class MessageController extends \BaseController {
    
    public function index() {
        $company = CompanyModel::find(Session::get('company_id'));
        $param['pageNo'] = 7;
        $param['company'] = $company;
        $param['messages'] = MessageModel::whereIn('company_id', $company->companyIds())->groupBy('user_id', 'job_id')->get();
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        return View::make('company.message.index')->with($param);
    }
    
    public function detail($companySlug, $userId, $jobId = NULL) {
        $company = CompanyModel::findBySlug($companySlug);
        $param['company'] = $company;
        $param['pageNo'] = 7;
        $param['jobId'] = $jobId;
        $param['userId'] = $userId;
        
        if (!Session::has('company_id')) {
            Session::set('company_is_admin', $company->is_admin);
            Session::set('company_id', $company->id);
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
        
        return View::make('company.message.detail')->with($param);
    }
}
