<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use Company as CompanyModel;
use Job as JobModel;
use Apply as ApplyModel;

class CompanyApplyRejectionMessage {

    public function fire($j, $d)
    {
        $companyId = $d['company_id'];
        $applyId = $d['apply_id'];
        $m_content = $d['m_content'];

        $company = CompanyModel::find($companyId);
        $apply = ApplyModel::find($applyId);
        $job = JobModel::find($apply->job->id);


        $email = EmailModel::findByCode('ET03');

        $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', $job->slug), $email->body);
        $body = str_replace('{content}', $m_content, $body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => $company->name,
            'reply_email' => $company->email,
            'email'       => $apply->user->email,
            'name'        => $apply->user->name,
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
