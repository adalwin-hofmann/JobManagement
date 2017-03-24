<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use Company as CompanyModel;

class InterviewNotiCompanyMessage {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $companyId = $d['company_id'];

        $cUser = UserModel::find($userId);
        $company = CompanyModel::find($companyId);

        $email = EmailModel::findByCode('ET13');

        $body = str_replace('{user_name}', $cUser->name, $email->body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => $cUser->name,
            'reply_email' => $cUser->email,
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
