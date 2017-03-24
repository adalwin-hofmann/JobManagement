<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use Company as CompanyModel;

class UserViewAllApplicationsMessage {

    public function fire($j, $d)
    {
        $companyId = $d['company_id'];
        $rdr = $d['rdr'];

        $company = CompanyModel::find($companyId);

        $email = EmailModel::findByCode('ET25');

        $body = str_replace('{rdr}', $rdr, $email->body);

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
