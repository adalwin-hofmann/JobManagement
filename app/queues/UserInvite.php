<?php namespace SH\Queue;

use Mail, URL;
use Email as EmailModel;
use User as UserModel;
use UserContact as UserContactModel;

class UserInvite {

    public function fire($j, $d)
    {
        $userId = $d['user_id'];
        $contactId = $d['contact_id'];

        $user = UserModel::find($userId);
        $contact = UserContactModel::find($contactId);

        $email = EmailModel::findByCode('ET15');

        $body = str_replace('{invite_name}', $contact->name, $email->body);
        $body = str_replace('{user_name}', $user->name, $body);
        $body = str_replace('{user_link}', URL::route('user.view', $user->slug), $body);
        $body = str_replace('{SITE_NAME}', SITE_NAME, $body);
        $body = str_replace('{signup_link}', URL::route('user.invite.signup', array($user->slug, $contact->id)), $body);

        $data = ['body' => $body];

        $info = [ 'reply_name'  => REPLY_NAME,
            'reply_email' => REPLY_EMAIL,
            'email'       => $contact->email,
            'name'        => $contact->name,
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
