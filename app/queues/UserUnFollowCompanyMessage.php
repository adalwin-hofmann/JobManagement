<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use Company as CompanyModel;

class UserUnFollowCompanyMessage {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $companyId = $d['company_id'];

        $user = UserModel::find($userId);
        $company = CompanyModel::find($companyId);

        $email = EmailModel::findByCode('ET22');

        $body = str_replace('{user_link}', URL::route('user.view', $user->slug), $email->body);
        $body = str_replace('{user_name}', $user->name, $body);

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
