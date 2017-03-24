<?php namespace Reminder;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Mail, Queue;

use JobRecommend as JobRecommendModel;
use User as UserModel;
use Email as EmailModel;

class ReminderController extends \BaseController {
        
    public function userRegister() {

        $date = new \DateTime('-3 days');
        $cDate = $date->format('Y-m-d').' 00:00:00';

        $hints = JobRecommendModel::where('created_at' < $cDate)->get();

        foreach ($hints as $hint) {

            $count = UserModel::where('email', $hint->email)->get()->count();

            if ($count == 0) {
                Queue::push('\SH\Queue\UserReminderMessage', ['hint_id' => $hint->id] );
            }

        }
    }
}