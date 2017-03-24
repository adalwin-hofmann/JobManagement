<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL;

use FaceInterview as FaceInterviewModel;
use Company as CompanyModel;
use User as UserModel;
use Email as EmailModel;
use CompanyVICreated as CompanyVICreatedModel;
use CompanyVIResponse as CompanyVIResponseModel;

class InterviewController extends \BaseController {

    public function face() {
        $company = CompanyModel::find(Session::get('company_id'));
        $param['faceInterviews'] = FaceInterviewModel::where('company_id', $company->id)
                                                     ->where('interview_date', '<>', '')
                                                     ->get();
        $param['company'] = $company;
        $param['pageNo'] = 10;
        return View::make('company.interview.face')->with($param);
    }

    public function video() {
        $company = CompanyModel::find(Session::get('company_id'));

        $cvcs = CompanyVICreatedModel::whereIn('company_id', $company->companyIds())->get();
        $cvcIds = array();
        foreach ($cvcs as $item) {
            if (CompanyVIResponseModel::where('cvc_id', $item->id)->get()->count() > 0) {
                $cvcIds[] = $item->id;
            }
        }

        $param['interviews'] = CompanyVICreatedModel::whereIn('id', $cvcIds)->paginate(PAGINATION_SIZE);
        $param['company'] = $company;
        $param['pageNo'] = 11;
        return View::make('company.interview.video')->with($param);
    }

    public function shared() {
        $company = CompanyModel::find(Session::get('company_id'));
        $param['company'] = $company;
        $param['pageNo'] = 12;

        return View::make('company.interview.shared')->with($param);
    }

    public function asyncFaceDoSchedule() {
        $companyId = Session::get('company_id');
        $userId = Input::get('user_id');
        $date = Input::get('date');
        $time = Input::get('time');
        $title = Input::get('title');
        $duration = Input::get('duration');
        $description = Input::get('description');
        
        $user = UserModel::find($userId);
        $company = CompanyModel::find($companyId);
        
        $faceInterview = new FaceInterviewModel;
        $faceInterview->company_id = $companyId;
        $faceInterview->user_id = $userId;
        $faceInterview->interview_date = $date;
        $faceInterview->start_at = date('H:i:00', strtotime($time) + 0);
        $faceInterview->duration = $duration;
        $faceInterview->title = $title;
        $faceInterview->description = $description;
        $faceInterview->save();
        
        $email = EmailModel::findByCode('ET38');
        
        $body = str_replace('{username}', $user->name, $email->body);
        $body = str_replace('{company_link}', URL::route('user.company.view', $company->slug), $body);
        $body = str_replace('{company_name}', $company->name, $body);
        $body = str_replace('{interview_date}', date("d-m-Y", strtotime($date)), $body);
        $body = str_replace('{start_at}', date('H:i:00', strtotime($time) + 0), $body);
        $body = str_replace('{end_at}', date('H:i:00', strtotime($time) + $duration * 60), $body);
        $body = str_replace('{title}', $title, $body);
        $body = str_replace('{description}', $description, $body);
        
        $data = ['body' => $body];
        
        $info = [ 'reply_name'  => $company->name,
                  'reply_email' => $company->email,
                  'email'       => $user->email,
                  'name'        => $user->name,
                  'subject'     => $email->subject, ];
        
        Mail::send('emails.blank', $data, function($message) use($info) {
            $message->from($info['reply_email'], $info['reply_name']);
            $message->to($info['email'], $info['name'])
                    ->subject($info['subject']);
        });
        
        return Response::json([ 'result' => 'success', 'msg' => 'You have scheduled a face to face interview successfully', ]);
    }
    
    public function asyncFaceDoInvite() {
        $companyId = Session::get('company_id');
        $userId = Input::get('user_id');
        $title = Input::get('title');
        $duration = Input::get('duration');
        $description = Input::get('description');
         
        $faceInterview = new FaceInterviewModel;
        $faceInterview->company_id = $companyId;
        $faceInterview->user_id = $userId;
        $faceInterview->duration = $duration;
        $faceInterview->title = $title;
        $faceInterview->description = $description;
        $faceInterview->save();
        
        $user = UserModel::find($userId);
        $company = CompanyModel::find($companyId);
         
        $email = EmailModel::findByCode('ET39');
         
        $body = str_replace('{username}', $user->name, $email->body);
        $body = str_replace('{company_link}', URL::route('user.company.view', $company->slug), $body);
        $body = str_replace('{company_name}', $company->name, $body);
        $body = str_replace('{title}', $title, $body);
        $body = str_replace('{duration}', $duration, $body);
        $body = str_replace('{description}', $description, $body);
        $body = str_replace('{book_link}', URL::route('interview.face.book', $faceInterview->id), $body);
         
        $data = ['body' => $body];
         
        $info = [ 'reply_name'  => $company->name,
                     'reply_email' => $company->email,
                  'email'       => $user->email,
                  'name'        => $user->name,
                  'subject'     => $email->subject, ];
         
        Mail::send('emails.blank', $data, function($message) use($info) {
            $message->from($info['reply_email'], $info['reply_name']);
            $message->to($info['email'], $info['name'])
                    ->subject($info['subject']);
        });        
        return Response::json([ 'result' => 'success', 'msg' => 'You have invite a face to face interview successfully', ]);
    }    
    
    public function asyncFaceLoadBookingInfo() {
        $faceInterview = FaceInterviewModel::find(Input::get('id'));
        return Response::json([ 'result' => 'success', 'msg' => '',
                                'id' => $faceInterview->user_id,
                                'name' => $faceInterview->user->name, 
                                'email' => $faceInterview->user->email, 
                                'title' => $faceInterview->title, 
                                'interview_date' => $faceInterview->interview_date,
                                'start_at' => $faceInterview->start_at,
                                'description' => $faceInterview->description, ]);
    }
}
