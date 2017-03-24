<?php namespace Agency;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Mail, Response, Cookie, URL;
use Company as CompanyModel;
use Job as JobModel;
use City as CityModel;
use Category as CategoryModel;
use Teamsize as TeamsizeModel;
use Service as ServiceModel;
use Email as EmailModel;


class AuthController extends \BaseController {
        
    public function login() {
        $param['pageNo'] = 98;
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('agency.auth.login')->with($param);
    }
    
    public function signup() {
        $param['pageNo'] = 99;      
        $param['cities'] = CityModel::where('name', '<>', '')->get();
        $param['teamsizes'] = TeamsizeModel::all();
        $param['categories'] = CategoryModel::all();
        $param['services']  = ServiceModel::all();
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;   
        }
        
        return View::make('agency.auth.signup')->with($param);
    }
    
    public function doSignup() {
    	
        $rules = ['name' 		=> 'required',
				  'password'   => 'required|confirmed',
                  'password_confirmation' => 'required',
                  'email' 		=> 'required|email',
                 ];
        
	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
            $password = Input::get('password');
            $agencyId = 0;
            
            $is_published = 0;
            
            if (Input::has('is_published')) {
            	$is_published = Input::get('is_published');
            }
            
			$agency = new CompanyModel;
			
			if ($password === '') {
				$alert['msg'] = 'You have to enter password';
				$alert['type'] = 'danger';
				return Redirect::route('agency.auth.signup')->with('alert', $alert);
			}
            $agency->salt = str_random(8);
            $agency->secure_key = md5($agency->salt.$password);
            $agency->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
            $agency->category_id = CategoryModel::whereRaw(true)->min('id');
            $agency->city_id = Input::get('city_id');
            $agency->name = Input::get('name');
            $agency->email = Input::get('email');
            $agency->logo = 'default_company_logo.gif';
            $agency->is_admin = 1;
            $agency->is_finished 	= 0;
            $agency->overlay_color = 'rgba(0, 82, 208, 0.9)';
            $agency->is_agency = 1;

            $agency->save();

            $agency->parent_id = $agency->id;
            $agency->save();


            $email = EmailModel::findByCode('ET12');
            
            $body = str_replace('{verify_code}', $agency->salt, $email->body);
            $body = str_replace('{verify_link}', URL::route('agency.auth.verify', $agency->slug), $body);
            $body = str_replace('{company_name}', $agency->name, $body);
            
            $data = ['body' => $body];
            
            $info = [ 'reply_name'  => REPLY_NAME,
                      'reply_email' => REPLY_EMAIL,
                      'email'       => $agency->email,
                      'name'        => $agency->name,
                      'subject'     => $email->subject,
                    ];
            
            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });
			            
            $alert['msg'] = 'Email has been send to you. Please check the email to verify your account.';
            $alert['type'] = 'success';
              
            return Redirect::route('agency.auth.signup')->with('alert', $alert);
	    }
    }
    
    public function verify($slug) {

        $agency = CompanyModel::where('slug', $slug)->where('is_agency', 1)->firstOrFail();

        $param['agency'] = $agency;

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        return View::make('agency.auth.verify')->with($param);
    }

    public function doVerify() {

        $agencyId = Input::get('agency_id');
        $salt = Input::get('verify_code');

        $agency = CompanyModel::find($agencyId);

        if ($agency->salt == $salt) {
            $agency->is_active = 1;
            $agency->is_spam = 0;
            $agency->save();

            $alert['msg'] = 'Successfully verified.';
            $alert['type'] = 'success';
            return Redirect::route('agency.auth.verify', $agency->slug)->with('alert', $alert);
        }else {
            $alert['msg'] = 'Verification Code is Wrong!!!';
            $alert['type'] = 'danger';
            return Redirect::route('agency.auth.verify', $agency->slug)->with('alert', $alert);
        }
    }

    
    public function doLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        $is_remember = Input::get('is_remember');
        
        $agency = CompanyModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?)) and is_agency=1', array($email, $password))->get();
        
        if (count($agency) != 0) {
            if ($agency[0]->is_active) {
                Session::set('agency_id', $agency[0]->id);
                Session::set('agency_is_admin', $agency[0]->is_admin);

                if ($is_remember == 1) {
                    Cookie::queue('ct', $agency[0]->salt, 60 * 24 * 60);
                }                

                if ($agency[0]->is_finished == 1) {
                    return Redirect::route('agency.dashboard');
                }else {
                    return Redirect::route('agency.profile');
                }

            }else {
                $alert['msg'] = 'Account is not verified yet.';
                $alert['type'] = 'danger';
                return Redirect::route('agency.auth.login')->with('alert', $alert);
            }
        } else {
            $alert['msg'] = 'Company & Password is incorrect';
            $alert['type'] = 'danger';
            return Redirect::route('agency.auth.login')->with('alert', $alert);
        }
    }
    
    public function doLogout() {
        Session::forget('agency_id');
        Cookie::queue('ct', '', -1);
        return Redirect::route('user.job.home');
    }



    public function resetPassword($agencySlug) {

        if (Input::has('_token')) {
            $agency = CompanyModel::findBySlug($agencySlug);
            $salt = Input::get('_token');

            if ($agency->salt == $salt) {

                $param['agency'] = $agency;
                if ($alert = Session::get('alert')) {
                    $param['alert'] = $alert;
                }
                return View::make('agency.auth.resetPassword')->with($param);

            }else {
                return View::make('404.index');
            }
        }else {
            return View::make('404.index');
        }
    }

    public function doResetPassword() {
        $confirmPassword = Input::get('confirm_password');
        $password = Input::get('password');
        $agencyId = Input::get('agencyId');
        $agency = CompanyModel::find($agencyId);


        if ($password == '') {
            $alert['msg'] = trans('auth.msg_21');
            $alert['type'] = 'danger';
            return Redirect::to('agency/auth/'. $agency->slug .'/reset/password?_token='.$agency->salt)->with('alert', $alert);
        }else {
            if ($password == $confirmPassword) {

                $agency->secure_key = md5($agency->salt.$password);
                $agency->save();

                $alert['msg'] = trans('auth.msg_23');
                $alert['type'] = 'success';
                return Redirect::to('agency/auth/'. $agency->slug .'/reset/password?_token='.$agency->salt)->with('alert', $alert);

            } else {
                $alert['msg'] = trans('auth.msg_22');
                $alert['type'] = 'danger';
                return Redirect::to('agency/auth/'. $agency->slug .'/reset/password?_token='.$agency->salt)->with('alert', $alert);
            }
        }
    }

    public function asyncResetPassword() {
        $email = Input::get('email');

        $count = CompanyModel::where('email', $email)->where('is_agency', 1)->get()->count();

        if ($count > 0) {
            $agency = CompanyModel::where('email', $email)->where('is_agency', 1)->firstOrFail();
            
            $email = EmailModel::findByCode('ET06');
            
            $body = str_replace('{reset_link}', URL::route('agency.auth.resetPassword', $agency->slug)."?_token=".$agency->salt, $email->body);
            $body = str_replace('{company_name}', $agency->name, $body);

            $data = ['body' => $body];
            
            $info = [ 'reply_name'  => REPLY_NAME,
                      'reply_email' => REPLY_EMAIL,
                      'email'       => $agency->email,
                      'name'        => $agency->name,
                      'subject'     => $email->subject,
                    ];
            
            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });

            return Response::json(['result' => 'success', 'msg' => trans('auth.msg_19')]);
        }else {
            return Response::json(['result' => 'failed', 'msg' => trans('auth.msg_18')]);
        }
    }
}