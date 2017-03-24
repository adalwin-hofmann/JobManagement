<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use JobRecommend as JobRecommendModel;
use Job as JobModel;

class UserVerifyHintMessage {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $jobId = $d['job_id'];
        $recommendId = $d['recommend_id'];

        $cuser = UserModel::find($userId);
        $recommend = JobRecommendModel::find($recommendId);
        $job = JobModel::find($jobId);

        $email = EmailModel::findByCode('ET24');

        $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', ['slug' => $job->slug]), $email->body);
        $body = str_replace('{user_link}', URL::route('user.view', $cuser->slug), $body);
        $body = str_replace('{verify_code}', $recommend->verifyCode, $body);
        $body = str_replace('{verify_link}', URL::route('user.job.verifyHint', ['slug' => $job->slug, 'id' => $recommend->id, ]), $body);
        $body = str_replace('{signup_link}', URL::route('user.auth.signup'), $body);
        $body = str_replace('{user_name}', $cuser->name, $body);
        $body = str_replace('{content}', $recommend->description, $body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => REPLY_NAME,
            'reply_email' => REPLY_EMAIL,
            'email'       => $recommend->email,
            'name'        => $recommend->name,
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
