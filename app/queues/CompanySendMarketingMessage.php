<?php namespace SH\Queue;

use Mail;
use Group as GroupModel;
use GroupMarketing as GroupMarketingModel;

class CompanySendMarketingMessage {

    public function fire($j, $d)
    {
        $groupMarketingId = $d['group_marketing_id'];
        
        $groupMarketing = GroupMarketingModel::find($groupMarketingId);
        $group = GroupModel::find($groupMarketing->group_id);
         
        foreach ($group->groupCompanies as $groupCompany) {
            $data = ['body' => $groupMarketing->body];
        
            $info = [ 'reply_name'  => $groupMarketing->reply_name,
                      'reply_email' => $groupMarketing->reply_email,
                      'email'       => $groupCompany->company->email,
                      'name'        => $groupMarketing->name,
                      'subject'     => $groupMarketing->subject,
            ];
        
            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });
        }
        
        $j->delete();
    }

} 
