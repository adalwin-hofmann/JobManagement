<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use Company as CompanyModel;

class CompanyRequestFeedbackMessage {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $companyId = $d['company_id'];
        $message_data = $d['message_data'];
        $memberId = $d['member_id'];

        $people = UserModel::find($userId);
        $member = CompanyModel::find($memberId);
        $c_member = CompanyModel::find($companyId);

        $email = EmailModel::findByCode('ET05');

        $body = str_replace('{user_link}', URL::route('user.view', $people->slug), $email->body);
        $body = str_replace('{content}', $message_data, $body);
        $body = str_replace('{member_name}', $c_member->name, $body);


        $data = ['body' => $body];

        $info = [ 'reply_name'  => REPLY_NAME,
            'reply_email' => REPLY_EMAIL,
            'email'       => $member->email,
            'name'        => $member->name,
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
