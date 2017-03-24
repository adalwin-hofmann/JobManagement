<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use JobRecommend as JobRecommendModel;

class UserReminderMessage {

    public function fire($j, $d)
    {
        $hintId = $d['hint_id'];

        $hint = JobRecommendModel::find($hintId);

        $email = EmailModel::findByCode('ET20');

        $body = str_replace('{userName}', $hint->name, $email->body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => REPLY_NAME,
            'reply_email' => REPLY_EMAIL,
            'email'       => $hint->email,
            'name'        => $hint->name,
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
