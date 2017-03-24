<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use Company as CompanyModel;
use Job as JobModel;

class CompanyNotiForSharingJobMessage {

    public function fire($j, $d)
    {
        $agencyId = $d['agency_id'];
        $companyId = $d['company_id'];
        $shareId = $d['share_id'];

        $agency = CompanyModel::find($agencyId);
        $company = CompanyModel::find($companyId);

        $email = EmailModel::findByCode('ET35');

        $body = str_replace('{agency_name}', $agency->name, $email->body);
        $body = str_replace('{agency_link}', URL::route('user.company.view', $agency->slug), $body);
        $body = str_replace('{share_link}', URL::route('company.share.viewOnApp', array($company->slug, $shareId)), $body);

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
