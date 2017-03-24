<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use Company as CompanyModel;

class CompanyAddCandidateRMessage {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $companyId = $d['company_id'];

        $user = UserModel::find($userId);
        $company = CompanyModel::find($companyId);

        $email = EmailModel::findByCode('ET10');

        $body = str_replace('{user_name}', $user->name, $email->body);
        $body = str_replace('{company_link}', URL::route('user.company.view', $company->slug), $body);
        $body = str_replace('{company_name}', $company->name, $body);

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
