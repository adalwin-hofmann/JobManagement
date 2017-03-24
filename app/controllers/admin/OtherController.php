<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use Admin as AdminModel;

class OtherController extends \BaseController {
    
    public function index() {

        $admin = AdminModel::find(Session::get('admin_id'));

        $param['pageNo'] = 24;
        $param['levelScore'] = $admin->level_score;
        $param['shareScore'] = $admin->share_score;
        $param['applyScore'] = $admin->apply_score;
        $param['recruitVerifyScore'] = $admin->recruit_verify_score;
        $param['recruitSuccessScore'] = $admin->recruit_success_score;
        $param['recruitScore'] = $admin->recruit_score;
        $param['inviteScore'] = $admin->invite_score;

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        return View::make('admin.other.index')->with($param);
    }



    //ajax functions

    public function asyncUpdateLevelScore() {
        $levelScore = Input::get('level_score');
        $shareScore = Input::get('share_score');
        $applyScore = Input::get('apply_score');
        $recruitVerifyScore = Input::get('recruit_verify_score');
        $recruitSuccessScore = Input::get('recruit_success_score');
        $inviteScore = Input::get('invite_score');
        $recruitScore = Input::get('recruit_score');

        $admin = AdminModel::find(Session::get('admin_id'));

        $admin->level_score = $levelScore;
        $admin->share_score = $shareScore;
        $admin->apply_score = $applyScore;
        $admin->recruit_verify_score = $recruitVerifyScore;
        $admin->recruit_success_score = $recruitSuccessScore;
        $admin->recruit_score = $recruitScore;
        $admin->invite_score = $inviteScore;

        $admin->save();

        return Response::json(['result' => 'success', 'msg' => 'Level Score updated successfully!']);
    }

}
