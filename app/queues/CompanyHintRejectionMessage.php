<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use Company as CompanyModel;
use Job as JobModel;
use JobRecommend as JobRecommendModel;

class CompanyHintRejectionMessage {

    public function fire($j, $d)
    {
        $companyId = $d['company_id'];
        $hindId = $d['hint_id'];
        $m_content = $d['m_content'];

        $company = CompanyModel::find($companyId);
        $hint = JobRecommendModel::find($hindId);


        $email = EmailModel::findByCode('ET04');

        $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', ['slug' => $hint->job->slug]), $email->body);
        $body = str_replace('{content}', $m_content, $body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => $company->name,
            'reply_email' => $company->email,
            'email'       => $hint->user->email,
            'name'        => $hint->user->name,
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
