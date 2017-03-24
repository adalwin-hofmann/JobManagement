<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, SendGrid, Mail, Queue;

use Company as CompanyModel;
use Review as ReviewModel;
use Pattern as PatternModel;
use User as UserModel;
use UserContact as UserContactModel;
use CompanyApply as CompanyApplyModel;
use Admin as AdminModel;
use Email as EmailModel;

class CompanyController extends \BaseController {
	
	public function view($slug) {
		
		$company = CompanyModel::where('slug', '=' , $slug)->get();		
		$id = $company[0]->id;
		
		$param['company'] = CompanyModel::find($id);
		$param['patterns'] = PatternModel::all();

        if (Session::has('agency_id')) {
            $param['agency'] = CompanyModel::find(Session::get('agency_id'));
        }
	
		if (Session::has('user_id')) {
			$param['userId'] = Session::get('user_id');
            $param['user'] = UserModel::find(Session::get('user_id'));
            $param['contacts'] = UserContactModel::where('user_id', Session::get('user_id'))->get();
            $param['patterns'] = PatternModel::where('user_id', Session::get('user_id'))->orWhereNull('user_id')->get();
		}else {
            $param['patterns'] = PatternModel::whereNull('user_id')->get();
        }


		return View::make('user.company.view')->with($param);
	}

    public function search() {
        $param['companies'] = CompanyModel::where('is_active', 1)->where('is_admin', 1)->where('is_spam', 0)->where('is_agency', 0)->get();
        $param['pageNo'] = 8;

        if (Session::has('user_id')) {
            $param['userId'] = Session::get('user_id');
            $param['user'] = UserModel::find(Session::get('user_id'));
        }

        return View::make('user.company.search')->with($param);
    }


    public function doApply() {
        $rules = [
            'name'       => 'required',
            'description'   => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }else {

            $companyId = Input::get('company_id');
            $userId = Session::get('user_id');
            $name = Input::get('name');
            $description = Input::get('description');

            $apply = new CompanyApplyModel;

            $apply->user_id = $userId;
            $apply->company_id = $companyId;
            $apply->name = $name;
            $apply->description = $description;
            $apply->token = str_random(32);


            if (Input::hasFile('attachFile')) {
                $filename = str_random(24).".".Input::file('attachFile')->getClientOriginalExtension();
                Input::file('attachFile')->move(ABS_UPLOAD_PATH, $filename);
                $apply->attached_file = $filename;
            }

            $apply->save();

            $company = CompanyModel::find($companyId);
            $cuser = UserModel::find($userId);

            $admin = AdminModel::whereRaw(true)->firstOrFail();
            $prevLevel = (int) ($cuser->score / $admin->level_score);
            $cuser->score = $cuser->score + $admin->apply_score;
            $currLevel = (int) ($cuser->score / $admin->level_score);

            if ($prevLevel != $currLevel) {
                $cuser->fb_share = 1;
            }

            $cuser->save();

            $rdr = str_replace("\r\n", "<br/>", $description);

            Queue::push('\SH\Queue\UserViewAllApplicationsMessage', ['company_id' => $companyId, 'rdr' => $rdr] );

            return Redirect::back();
        }
    }



    /* ajax functions */
	public function asyncAddReview() {
		if (Session::has('user_id')) {
			if (Input::has('company_id')) {
				$companyId = Input::get('company_id');
				$score = Input::get('score');
				$description = Input::get('description');
				$userId = Session::get('user_id');
				
				$status = ReviewModel::where('company_id', $companyId)->where('user_id', $userId)->count();
	
				if ($status == 0) {
					
					$review = new ReviewModel;
					
					$review->user_id = $userId;
					$review->company_id = $companyId;
					$review->score = $score;
					$review->description = $description;
					
					$review->save();
									
					return Response::json(['result' => 'success', 'msg' => 'Review has been submitted successfully.']);
				} else {
					return Response::json(['result' => 'failed', 'msg' => 'You have already leaved a review', 'code' => 'CD00']);
				}
			} else {
				return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', 'code' => 'CD00']);
			}
		} else {
			return Response::json(['result' => 'failed', 'msg' => 'You must login for leave review', 'code' => 'CD01']);
		}
	}
}
