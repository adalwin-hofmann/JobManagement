<?php namespace Agency;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, URL, Queue;

use Company as CompanyModel;
use AgencyShare as AgencyShareModel;


class ShareController extends \BaseController {

    public function index() {
        if (!Session::has('agency_id')) {
            return Redirect::route('agency.auth.login');
        }else {

            $agency = CompanyModel::find(Session::get('agency_id'));

            $param['agency'] = $agency;
            $param['pageNo'] = 9;

            return View::make('agency.share.index')->with($param);
        }
    }

    public function asyncGetCompanyListByJob() {

        if (Session::has('agency_id')) {

            $agency = CompanyModel::find(Session::get('agency_id'));
            $jobId = Input::get('job_id');

            if ($agency->agencyShares()->where('job_id', $jobId)->get()->count() > 0) {
                return Response::json(['result' => 'fail', 'msg' => 'You already shared this job.']);
            }

            $param['agency'] = $agency;

            return Response::json(['result' => 'success', 'companyView' => View::make('agency.company.ajaxList')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncGetCompanyListByUser() {

        if (Session::has('agency_id')) {

            $agency = CompanyModel::find(Session::get('agency_id'));
            $userId = Input::get('user_id');

            if ($agency->agencyShares()->where('user_id', $userId)->get()->count() > 0) {
                return Response::json(['result' => 'fail', 'msg' => 'You already shared this user.']);
            }

            $param['agency'] = $agency;

            return Response::json(['result' => 'success', 'companyView' => View::make('agency.company.ajaxList')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

    public function asyncGetCompanyListByInterview() {

        if (Session::has('agency_id')) {

            $agency = CompanyModel::find(Session::get('agency_id'));
            $interviewId = Input::get('interview_id');

            if ($agency->agencyShares()->where('interview_id', $interviewId)->get()->count() > 0) {
                return Response::json(['result' => 'fail', 'msg' => 'You already shared this interview.']);
            }

            $param['agency'] = $agency;

            return Response::json(['result' => 'success', 'companyView' => View::make('agency.company.ajaxList')->with($param)->__toString()]);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncShareToCompany() {
        if (Session::has('agency_id')) {

            $agency = CompanyModel::find(Session::get('agency_id'));
            $jobId = Input::get('job_id');
            $userId = Input::get('user_id');
            $interviewId = Input::get('interview_id');
            $companyId = Input::get('company_id');

            $share = new AgencyShareModel;

            $share->agency_id = $agency->id;
            $share->company_id = $companyId;

            if ($jobId != '') {
                $share->job_id = $jobId;
                Queue::push('\SH\Queue\CompanyNotiForSharingJobMessage', ['agency_id' => Session::get('agency_id'), 'company_id' => $companyId, 'share_id' => $share->id] );
            }

            if ($userId != '') {
                $share->user_id = $userId;
                Queue::push('\SH\Queue\CompanyNotiForSharingUserMessage', ['agency_id' => Session::get('agency_id'), 'company_id' => $companyId, 'share_id' => $share->id] );
            }

            if ($interviewId != '') {
                $share->interview_id = $interviewId;
                Queue::push('\SH\Queue\CompanyNotiForSharingInterviewMessage', ['agency_id' => Session::get('agency_id'), 'company_id' => $companyId, 'share_id' => $share->id] );
            }

            $share->save();

            return Response::json(['result' => 'success', 'msg' => 'Successfully shared to company.']);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }


    public function asyncRemoveShare() {

        if (Session::has('agency_id')) {

            $shareId = Input::get('share_id');

            AgencyShareModel::find($shareId)->delete();

            return Response::json(['result' => 'success', 'companyView' => 'Disabled successfully.']);
        }else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

}
