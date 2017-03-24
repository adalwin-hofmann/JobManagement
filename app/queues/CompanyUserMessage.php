<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use Company as CompanyModel;
use Job as JobModel;

class CompanyUserMessage {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $companyId = $d['company_id'];
        $message_data = $d['message_data'];
        $jobId = $d['job_id'];

        $user = UserModel::find($userId);
        $company = CompanyModel::find($companyId);
        $job = JobModel::find($jobId);

        $email = EmailModel::findByCode('ET01');

        $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', $job->slug), $email->body);
        $body = str_replace('{job_name}', $job->name, $body);
        $body = str_replace('{company_link}', URL::route('user.company.view', $company->slug), $body);
        $body = str_replace('{company_name}', $company->name, $body);
        $body = str_replace('{content}', $message_data, $body);
        $body = str_replace('{message_link}', URL::route('user.message.detail', array($user->slug, $company->id, $job->id)), $body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => $company->name,
            'reply_email' => $company->email,
            'email'       => $user->email,
            'name'        => $user->name,
            'subject'     => $email->subject,
        ];

        Mail::send('emails.blank', $data, function($message) use($info) {
            $message->from($info['reply_email'], $info['reply_name']);
            $message->to($info['email'], $info['name'])
                ->subject($info['subject']);
        });

        $j->delete();
    }

} 
