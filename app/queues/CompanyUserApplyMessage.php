<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use Company as CompanyModel;

class CompanyUserApplyMessage {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $companyId = $d['company_id'];
        $message_data = $d['message_data'];

        $user = UserModel::find($userId);
        $company = CompanyModel::find($companyId);

        $email = EmailModel::findByCode('ET26');

        $body = str_replace('{company_link}', URL::route('user.company.view', $company->slug), $email->body);
        $body = str_replace('{company_name}', $company->name, $body);
        $body = str_replace('{content}', $message_data, $body);
        $body = str_replace('{message_link}', URL::route('user.message.detail', array($user->slug, $company->id)), $body);

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
