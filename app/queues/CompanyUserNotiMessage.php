<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use Company as CompanyModel;
use Job as JobModel;

class CompanyUserNotiMessage {

    public function fire($j, $d)
    {
        $companyId = $d['company_id'];
        $jobId = $d['job_id'];

        $company = CompanyModel::find($companyId);
        $job = JobModel::find($jobId);

        $email = EmailModel::findByCode('ET11');

        foreach ($company->followUsers as $fuser) {
            $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', ['slug' => $job->slug, ]), $email->body);
            $body = str_replace('{company_link}', URL::route('user.company.view', $company->slug), $body);
            $body = str_replace('{company_name}', $company->name, $body);

            $data = ['body' => $body];

            $info = [ 'reply_name'  => REPLY_NAME,
                'reply_email' => REPLY_EMAIL,
                'email'       => $fuser->user->email,
                'name'        => $fuser->user->name,
                'subject'     => $email->subject,
            ];

            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                    ->subject($info['subject']);
            });
        }

        $j->delete();
    }

} 
