<?php namespace Company;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use Company as CompanyModel;

class AgencyController extends \BaseController {

    public function asyncView() {
        if (Session::has('company_id')) {
            $agencyId = Input::get('agency_id');

            $agency = CompanyModel::find($agencyId);
            $company = CompanyModel::find(Session::get('company_id'));

            $param['agency'] = $agency;
            $param['company'] = $company;

            return Response::json(['result' => 'success', 'agencyView' => View::make('company.agency.ajaxView')->with($param)->__toString()]);
        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }
    }

}
