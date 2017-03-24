<?php namespace Company;

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
        
        return View::make('company.auth.login')->with($param);        
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
        
        return View::make('company.auth.signup')->with($param);
    }
    
    public function doSignup() {
        $rules = ['name'         => 'required',
                  'password'    => 'required|confirmed',
                  'password_confirmation' => 'required',
                  'email'         => 'required|email',
                 ];
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $password = Input::get('password');
            $is_published = Input::has('is_published') ? Input::get('is_published') : 0;
            
            if ($password == '') {
                $alert['msg'] = 'You have to enter password';
                $alert['type'] = 'danger';
                return Redirect::route('company.auth.signup')->with('alert', $alert);
            }
            
            $company = new CompanyModel;
            $company->salt = str_random(8);
            $company->secure_key = md5($company->salt.$password);                      
            $company->teamsize_id = TeamsizeModel::whereRaw(true)->min('id');
            $company->category_id = CategoryModel::whereRaw(true)->min('id');
            $company->city_id = Input::get('city_id');
            $company->name = Input::get('name');
            $company->email = Input::get('email');
            $company->logo = DEFAULT_COMPANY_PHOTO;
            $company->is_admin = 1;
            $company->is_finished     = 0;
            $company->overlay_color = DEFAULT_COMPANY_OVERLAY_COLOR;
            $company->save();

            $company->parent_id = $company->id;
            $company->save();
            
            $email = EmailModel::findByCode('ET12');
            
            $body = str_replace('{verify_code}', $company->salt, $email->body);
            $body = str_replace('{verify_link}', URL::route('company.auth.verify', $company->slug), $body);
            $body = str_replace('{company_name}', $company->name, $body);
            
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

            $alert['msg'] = 'Email has been send to you. Please check the email to verify your account.';
            $alert['type'] = 'success';
              
            return Redirect::route('company.auth.signup')->with('alert', $alert);        
        }
    }
    
    public function referSignup() {
        $company = CompanyModel::find(Input::get('company_id'));
        
        $company->is_spam = FALSE;
        $company->is_finished = TRUE;
        $company->is_active = TRUE;
        $company->secure_key = md5($company->salt.Input::get('password'));
        $company->email = Input::get('email');
        $company->name = Input::get('name');
        $company->phone = Input::get('phone');
        $company->description = Input::get('description');
        $company->save();
        
        $company->parent_id = $company->id;
        $company->save();
        
        $alert['msg'] = trans('company.msg_signup_success');
        $alert['type'] = 'success';
        $job = JobModel::find(Input::get('job_id'));
        
        Session::set('company_is_admin', $company->is_admin);
        Session::set('company_id', $company->id);
        
        return Redirect::route('company.job.view', $job->slug)->with('alert', $alert);        
    }    


    public function verify($slug) {
        $company = CompanyModel::where('slug', $slug)->firstOrFail();

        $param['company'] = $company;

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        return View::make('company.auth.verify')->with($param);
    }

    public function doVerify() {

        $companyId = Input::get('company_id');
        $salt = Input::get('verify_code');

        $company = CompanyModel::find($companyId);

        if ($company->salt == $salt) {
            $company->is_active = 1;
            $company->is_spam = 0;
            $company->save();

            $alert['msg'] = 'Successfully verified.';
            $alert['type'] = 'success';
            return Redirect::route('company.auth.verify', $company->slug)->with('alert', $alert);
        } else {
            $alert['msg'] = 'Verification Code is Wrong!!!';
            $alert['type'] = 'danger';
            return Redirect::route('company.auth.verify', $company->slug)->with('alert', $alert);
        }
    }

    public function doMemberSignUp() {
        $rules = ['password'    => 'required|confirmed',
                  'password_confirmation' => 'required',
                  'email'         => 'required|email',
                 ];
    
        $validator = Validator::make(Input::all(), $rules);
         
        if ($validator->fails()) {
            return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
        } else {
            $email = Input::get('email');
            $count = CompanyModel::where('email', $email)->get()->count();
            if ($count == 0) {
                $alert['msg'] = 'Your email is not registered yet by admin.';
                $alert['type'] = 'danger';
                $param['alert'] = $alert;
                return Redirect::route('company.auth.signup')->with($param);                
            } else {
                $password = Input::get('password');
                $is_published = Input::has('is_published') ? Input::get('is_published') : 0;
                
                $company = CompanyModel::where('email', $email)->firstOrFail();
                if (strlen($company->secure_key) > 0) {
                    $param['alert'] = ['msg' => 'Your account is already registered.', 'type' => 'danger', ];
                    return Redirect::back()->with($param);
                } else {
                    if ($password == '') {
                        $alert['msg'] = 'You have to enter password';
                        $alert['type'] = 'danger';
                        return Redirect::route('company.auth.signup')->with('alert', $alert);
                    }
                    $company->salt = str_random(8);
                    $company->secure_key = md5($company->salt.$password);
                    $company->save();
                    
                    $param['alert'] = ['msg' => 'Your account has been saved successfully', 'type' => 'success', ];
                    return Redirect::route('company.auth.signup')->with($param);
                }
            }
        }
    }
    
    public function doLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        $is_remember = Input::get('is_remember');
        
        $company = CompanyModel::where('email', $email)
                               ->whereRaw('secure_key = md5(concat(salt, ?))', [$password])->get();
        if (count($company) != 0) {
            if ($company[0]->is_active) {
                Session::set('company_is_admin', $company[0]->is_admin);
                Session::set('company_id', $company[0]->id);
                
                if ($is_remember == 1) {
                    Cookie::queue('ct', $company[0]->salt, 60 * 24 * 60);
                }                

                if ($company[0]->is_finished == 1) {
                    return Redirect::route('company.dashboard');
                } else {
                    return Redirect::route('company.profile');
                }

            } else {
                $alert['msg'] = 'Account is not verified yet.';
                $alert['type'] = 'danger';
                return Redirect::route('company.auth.login')->with('alert', $alert);
            }
        } else {
            $alert['msg'] = 'Company & Password is incorrect';
            $alert['type'] = 'danger';
            return Redirect::route('company.auth.login')->with('alert', $alert);
        }
    }
    
    public function doLogout() {
        Session::forget('company_id');
        Cookie::queue('ct', '', -1);
        return Redirect::route('user.job.home');
    }
    
    public function resetPassword($companySlug) {
        if (Input::has('_token')) {
            $company = CompanyModel::findBySlug($companySlug);
            $salt = Input::get('_token');

            if ($company->salt == $salt) {
                $param['company'] = $company;
                if ($alert = Session::get('alert')) {
                    $param['alert'] = $alert;
                }
                return View::make('company.auth.resetPassword')->with($param);
            } else {
                return View::make('404.index');
            }
        } else {
            return View::make('404.index');
        }
    }

    public function doResetPassword() {
        $confirmPassword = Input::get('confirm_password');
        $password = Input::get('password');
        $companyId = Input::get('companyId');
        $company = CompanyModel::find($companyId);

        if ($password == '') {
            $alert['msg'] = trans('auth.msg_21');
            $alert['type'] = 'danger';
        } else {
            if ($password == $confirmPassword) {
                $company->secure_key = md5($company->salt.$password);
                $company->save();
                $alert['msg'] = trans('auth.msg_23');
                $alert['type'] = 'success';
            } else {
                $alert['msg'] = trans('auth.msg_22');
                $alert['type'] = 'danger';
            }
        }
        return Redirect::to(URL::route('company.auth.resetPassword', $company->slug).'?_token='.$company->salt)->with('alert', $alert);
    }

    public function asyncResetPassword() {
        $email = Input::get('email');
        $count = CompanyModel::where('email', $email)->get()->count();
        if ($count > 0) {
            $company = CompanyModel::where('email', $email)->firstOrFail();
            
            $email = EmailModel::findByCode('ET06');
            $body = str_replace('{reset_link}', URL::route('company.auth.resetPassword', $company->slug)."?_token=".$company->salt, $email->body);
            $body = str_replace('{company_name}', $company->name, $body);
            
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
            return Response::json(['result' => 'success', 'msg' => trans('auth.msg_19')]);
        } else {
            return Response::json(['result' => 'failed', 'msg' => trans('auth.msg_18')]);
        }
    }
}
