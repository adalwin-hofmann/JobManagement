<?php namespace SH\Queue;

use Mail, URL;
use UserFollowCompany as UserFollowCompanyModel;
use FollowCompany as FollowCompanyModel;
use Email as EmailModel;

class UserNotiMessage {

    public function fire($j, $d)
    {
        $userFollowCompanyId = $d['uf_company_id'];
        $followCompanyId = $d['f_company_id'];

        $ufcompany = UserFollowCompanyModel::find($userFollowCompanyId);
        $fCompany = FollowCompanyModel::find($followCompanyId);

        $email = EmailModel::findByCode('ET19');

        $body = str_replace('{user_link}', URL::route('user.view', $ufcompany->user->slug), $email->body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => REPLY_NAME,
            'reply_email' => REPLY_EMAIL,
            'email'       => $fCompany->company->email,
            'name'        => $fCompany->company->name,
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
