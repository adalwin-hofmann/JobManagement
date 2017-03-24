<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use Company as CompanyModel;
use Job as JobModel;

class UserMessage {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $companyId = $d['company_id'];
        $message_data = $d['message_data'];
        $jobId = $d['job_id'];

        $user = UserModel::find($userId);
        $company = CompanyModel::find($companyId);
        $job = JobModel::find($jobId);

        $email = EmailModel::findByCode('ET18');

        $body = str_replace('{job_link}', URL::route('company.job.view', $job->slug), $email->body);
        $body = str_replace('{job_name}', $job->name, $body);
        $body = str_replace('{user_link}', URL::route('user.view', $user->slug), $body);
        $body = str_replace('{user_name}', $user->name, $body);
        $body = str_replace('{content}', $message_data, $body);
        $body = str_replace('{message_link}', URL::route('company.message.detail', array($company->slug, $user->id, $job->id)), $body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => REPLY_NAME,
            'reply_email' => REPLY_EMAIL,
            'email'       => $company->email,
            'name'        => $company->name,
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
