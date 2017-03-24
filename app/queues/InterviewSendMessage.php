<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use Company as CompanyModel;
use CompanyVICreated as CompanyVICreatedModel;

class InterviewSendMessage {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $companyId = $d['company_id'];
        $cvcId = $d['cvc_id'];

        $cUser = UserModel::find($userId);
        $company = CompanyModel::find($companyId);
        $viCreated = CompanyVICreatedModel::find($cvcId);

        $email = EmailModel::findByCode('ET14');

        $content = str_replace("\n", "<br/>", $viCreated->description);

        $body = str_replace('{content}', $content, $email->body);
        $body = str_replace('{interview_link}', URL::route('interview.video.step1', ['slug' => $cUser->slug, 'company_slug' => $company->slug, ])."?_token=".$viCreated->token, $body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => $company->name,
            'reply_email' => $company->email,
            'email'       => $cUser->email,
            'name'        => $cUser->name,
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
