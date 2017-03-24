<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Hybrid_Auth, Hybrid_Endpoint, Mail, Response, Cookie, Log, URL;
use City as CityModel, User as UserModel;
use Category as CategoryModel;
use Level as LevelModel;
use Language as LanguageModel;
use Type as TypeModel;
use UserSkill as UserSkillModel;
use UserLanguage as UserLanguageModel;
use UserEducation as UserEducationModel;
use UserAwards as UserAwardsModel;
use UserExperience as UserExperienceModel;
use UserTestimonial as UserTestimonialModel;
use UserSns as UserSnsModel;
use UserContact as UserContactModel;
use Admin as AdminModel;
use Email as EmailModel;

class AuthController extends \BaseController {

    public function login() {
        $param['pageNo'] = 98;
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
        return View::make('user.auth.login')->with($param);        
    }
    
    public function signup() {
        $param['pageNo'] = 99;
        $param['cities'] = CityModel::where('name', '<>', '')->get();
        $param['categories'] = CategoryModel::all();
        $param['levels'] = LevelModel::all();
        $param['languages'] = LanguageModel::all();
        $param['types'] = TypeModel::all();
        
        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;   
        }
        
        return View::make('user.auth.signup')->with($param);
    }

    public function candidateSignUp($userSlug) {

        if (!UserModel::findBySlug($userSlug) || !Input::has('_token')) {
            return View::make('404.index');
        }

        $user = UserModel::findBySlug($userSlug);

        if ($user->salt != Input::get('_token')) {
            return View::make('404.index');
        }

        $param['pageNo'] = 99;
        $param['cities'] = CityModel::where('name', '<>', '')->get();
        $param['categories'] = CategoryModel::all();
        $param['levels'] = LevelModel::all();
        $param['languages'] = LanguageModel::all();
        $param['types'] = TypeModel::all();
        $param['user'] = UserModel::findBySlug($userSlug);

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        return View::make('user.auth.candidateSignUp')->with($param);
    }

    public function inviteSignUp($userSlug, $contactId) {
        if (!UserModel::findBySlug($userSlug)) {
            return View::make('404.index');
        }

        $user = UserModel::findBySlug($userSlug);
        $contact = UserContactModel::find($contactId);


        $param['pageNo'] = 99;
        $param['cities'] = CityModel::where('name', '<>', '')->get();
        $param['categories'] = CategoryModel::all();
        $param['levels'] = LevelModel::all();
        $param['languages'] = LanguageModel::all();
        $param['types'] = TypeModel::all();
        $param['user'] = $user;
        $param['contact'] = $contact;

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        return View::make('user.auth.inviteSignUp')->with($param);
    }
    
    public function doSignup() {
        $rules = ['password'   => 'required|confirmed',
                  'password_confirmation' => 'required',
                  'name'       => 'required',
                  'city_id'    => 'required',
                  'email'	   => 'required|email|unique:user',
                ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $user = new UserModel;
            
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->gender = 0;
            $user->category_id = CategoryModel::whereRaw(true)->min('id');
            $user->city_id = Input::get('city_id');
            $user->level_id = LevelModel::whereRaw(true)->min('id');
            $user->native_language_id = LanguageModel::whereRaw(true)->min('id');
            $user->salt = str_random(8);
            $user->secure_key = md5($user->salt.Input::get('password'));
            $user->profile_image = LOGO;
            $user->cover_image = DEFAULT_COVER_PHOTO;
            $user->save();
            
            $email = EmailModel::findByCode('ET23');
            
            $body = str_replace('{verify_code}', $user->salt, $email->body);
            $body = str_replace('{verify_link}', URL::route('user.auth.verify', $user->slug), $body);
            $body = str_replace('{user_name}', $user->name, $body);
            
            $data = ['body' => $body];
            
            $info = [ 'reply_name'  => REPLY_NAME,
                      'reply_email' => REPLY_EMAIL,
                      'email'       => $user->email,
                      'name'        => $user->name,
                      'subject'     => $email->subject,
                    ];
            
            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });
            
            $alert['msg'] = 'Email has been send to you. Please check the email to verify your account.';
            $alert['type'] = 'success';
            
            return Redirect::route('user.auth.signup')->with('alert', $alert);            
        }
    }

    public function doInviteSignUp() {
        $rules = ['password'   => 'required|confirmed',
            'password_confirmation' => 'required',
            'name'       => 'required',
            'city_id'    => 'required',
            'email'	   => 'required|email|unique:user',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $user = new UserModel;

            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->gender = 0;
            $user->category_id = CategoryModel::whereRaw(true)->min('id');
            $user->city_id = Input::get('city_id');
            $user->level_id = LevelModel::whereRaw(true)->min('id');
            $user->native_language_id = LanguageModel::whereRaw(true)->min('id');
            $user->salt = str_random(8);
            $user->secure_key = md5($user->salt.Input::get('password'));
            $user->profile_image = LOGO;
            $user->cover_image = DEFAULT_COVER_PHOTO;
            $user->is_active = 1;
            $user->save();

            $userId = Input::get('userId');
            $contactId = Input::get('contactId');

            $cuser = UserModel::find($userId);

            $admin = AdminModel::whereRaw(true)->firstOrFail();
            $prevLevel = (int) ($cuser->score / $admin->level_score);
            $cuser->score = $cuser->score + $admin->invite_score;
            $currLevel = (int) ($cuser->score / $admin->level_score);

            if ($prevLevel != $currLevel) {
                $cuser->fb_share = 1;
            }

            $cuser->save();

            $alert['msg'] = 'Your account has been registered successfully.';
            $alert['type'] = 'success';

            return Redirect::route('user.invite.signup', array($cuser->slug, $contactId))->with('alert', $alert);
        }
    }

    public function doCandidateSignUp() {
        $rules = ['password'   => 'required|confirmed',
            'password_confirmation' => 'required',
            'name'       => 'required',
            'city_id'    => 'required',
            'email'	   => 'required|email|unique:user',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $userId = Input::get('userId');

            $user = UserModel::find($userId);
            $user->salt = str_random(8);
            $user->secure_key = md5($user->salt.Input::get('password'));
            $user->save();

            $alert['msg'] = 'Your account has been registered successfully.';
            $alert['type'] = 'success';

            return Redirect::route('user.auth.candidateSignUp', $user->slug)->with('alert', $alert);
        }
    }


    public function verify($slug) {

        $user = UserModel::where('slug', $slug)->firstOrFail();

        $param['user'] = $user;

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        return View::make('user.auth.verify')->with($param);
    }

    public function doVerify() {

        $userId = Input::get('user_id');
        $salt = Input::get('verify_code');

        $user = UserModel::find($userId);

        if ($user->salt == $salt) {
            $user->is_active = 1;
            $user->save();

            $alert['msg'] = 'Successfully verified.';
            $alert['type'] = 'success';
            return Redirect::route('user.auth.verify', $user->slug)->with('alert', $alert);
        }else {
            $alert['msg'] = 'Verification Code is Wrong!!!';
            $alert['type'] = 'danger';
            return Redirect::route('user.auth.verify', $user->slug)->with('alert', $alert);
        }
    }
    
    public function doLogin() {
        $email = Input::get('email');
        $password = Input::get('password');
        $is_remember = Input::get('is_remember');
        
        $user = UserModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))->get();
        
        if (count($user) != 0) {

            if ($user[0]->is_active == 1) {
                Session::set('user_id', $user[0]->id);
                
                if ($is_remember == 1) {
                    Cookie::queue('ut', $user[0]->salt, 60 * 24 * 60);
                }

                $user = UserModel::find(Session::get('user_id'));
                $user->touch();
                
                if ($user->is_finished == 0) {
                    return Redirect::route('user.dashboard.profile');
                }else {
                    return Redirect::route('user.job.home');
                }
            }else {
                $alert['msg'] = 'You are not verified yet.';
                $alert['type'] = 'danger';
                return Redirect::route('user.auth.login')->with('alert', $alert);
            }

        } else {
            $alert['msg'] = 'Email & Password is incorrect.';
            $alert['type'] = 'danger';
            return Redirect::route('user.auth.login')->with('alert', $alert);
        }
    }


    public function doResetPassword() {
        $confirmPassword = Input::get('confirm_password');
        $password = Input::get('password');
        $userId = Input::get('userId');
        $user = UserModel::find($userId);


        if ($password == '') {
            $alert['msg'] = trans('auth.msg_21');
            $alert['type'] = 'danger';
            return Redirect::to('auth/'. $user->slug .'/reset/password?_token='.$user->salt)->with('alert', $alert);
        }else {
            if ($password == $confirmPassword) {

                $user->secure_key = md5($user->salt.$password);
                $user->save();

                $alert['msg'] = trans('auth.msg_23');
                $alert['type'] = 'success';
                return Redirect::to('auth/'. $user->slug .'/reset/password?_token='.$user->salt)->with('alert', $alert);

            } else {
                $alert['msg'] = trans('auth.msg_22');
                $alert['type'] = 'danger';
                return Redirect::to('auth/'. $user->slug .'/reset/password?_token='.$user->salt)->with('alert', $alert);
            }
        }
    }
    
    public function doLogout() {
        Session::forget('user_id');
        Cookie::queue('ut', '', -1);

        $fauth = new Hybrid_Auth(app_path().'/config/facebook_auth.php');
        $fauth->logoutAllProviders();

        $gauth = new Hybrid_Auth(app_path().'/config/googleplus_auth.php');
        $gauth->logoutAllProviders();
        
        $lauth = new Hybrid_Auth(app_path().'/config/linkedin_auth.php');
        $lauth->logoutAllProviders();

        return Redirect::route('user.job.home');
    }


    public function doFacebookLogin($auth=NULL) {
        if ($auth == 'auth') {
            try{
                Hybrid_Endpoint::process();
            }
            catch(Exception $e) {

                $alert['msg'] = 'Error occurred while getting facebook profile information.';
                $alert['type'] = 'danger';

                return Redirect::route('user.auth.login')->with('alert', $alert);
            }
        }

        $fauth = new Hybrid_Auth(app_path().'/config/facebook_auth.php');
        $provider = $fauth->authenticate("Facebook");
        
        $userId = $this->socialLogin("FB", $provider);
        Session::set('user_id', $userId);        
        
        return Redirect::route('user.job.home');        
    }
    
    public function doLinkedinLogin($auth=NULL) {
        if ($auth == 'auth') {
            try{
                Hybrid_Endpoint::process();
            }
            catch(Exception $e) {
    
                $alert['msg'] = 'Error occurred while getting facebook profile information.';
                $alert['type'] = 'danger';
    
                return Redirect::route('user.auth.login')->with('alert', $alert);
            }
        }
    
        $lauth = new Hybrid_Auth(app_path().'/config/linkedin_auth.php');
        $provider = $lauth->authenticate("LinkedIn");
        
        $userId = $this->socialLogin("LI", $provider);
        Session::set('user_id', $userId);        

        return Redirect::route('user.job.home');
    }    

    public function doGoogleLogin($auth=NULL) {

        if ($auth == 'auth') {
            try{
                Hybrid_Endpoint::process();
            }
            catch(Exception $e) {

                $alert['msg'] = 'Error occurred while getting google profile information.';
                $alert['type'] = 'danger';

                return Redirect::route('user.auth.login')->with('alert', $alert);
            }
        }

        $gauth = new Hybrid_Auth(app_path().'/config/googleplus_auth.php');
        $provider = $gauth->authenticate("Google");

        $userId = $this->socialLogin("GP", $provider);
        Session::set('user_id', $userId);
        return Redirect::route('user.job.home');
    }
    
    public function socialLogin($type, $provider) {
        $profile = $provider->getUserProfile();
        $access_token = $provider->getAccessToken()['access_token'];
        
        $email = $profile->email;
        $name = $profile->displayName;
        $profileURL =$profile->profileURL;
        $photoURL = $profile->photoURL;
        $snsId = $profile->identifier;
        $gender = ($profile->gender == 'female') ? 1 : 0;
        $count = UserModel::where('email', $email)->get()->count();
        
        if ($count > 0) {
            $user = UserModel::findByEmail($email);
            if ($type == "GP") {
                $user->google = $profileURL;
            } elseif ($type == "FB") {
                $user->facebook = $profileURL;
            } else {
                $user->linkedin = $profileURL;
            }
            
            $user->save();
        }else {
            $photoContent = file_get_contents($photoURL);
            $photoFilename = str_random(24).".jpg";
            file_put_contents(ABS_PHOTO_PATH.$photoFilename, $photoContent);
        
            $user = new UserModel;
        
            $user->name = $name;
            $user->email = $email;
            $user->gender = $gender;
            $user->category_id = CategoryModel::whereRaw(true)->min('id');
            $user->city_id = CityModel::whereRaw(true)->min('id');
            $user->level_id = LevelModel::whereRaw(true)->min('id');
            $user->native_language_id = LanguageModel::whereRaw(true)->min('id');
            $user->salt = str_random(8);
            $user->secure_key = md5($user->salt);
            $user->profile_image = $photoFilename;
            $user->cover_image = DEFAULT_COVER_PHOTO;
            $user->save();
        }
        
        $user->touch();
        
        $userSnses = UserSnsModel::where('type', $type)->where('user_id', $user->id)->get();
        if (count($userSnses) == 0) {
            $userSns = new UserSnsModel;
            $userSns->user_id = $user->id;
            $userSns->type = 'GP';
            $userSns->sns_id = $snsId;
            $userSns->token = $access_token;
            $userSns->save();
        }
        return $user->id;
    }

    public function resetPassword($userSlug) {

        if (Input::has('_token')) {
            $user = UserModel::findBySlug($userSlug);
            $salt = Input::get('_token');

            if ($user->salt == $salt) {

                $param['user'] = $user;
                if ($alert = Session::get('alert')) {
                    $param['alert'] = $alert;
                }
                return View::make('user.auth.resetPassword')->with($param);

            }else {
                return View::make('404.index');
            }
        }else {
            return View::make('404.index');
        }
    }

    public function asyncResetPassword() {
        $email = Input::get('email');

        $count = UserModel::where('email', $email)->get()->count();

        if ($count > 0) {
            $user = UserModel::where('email', $email)->firstOrFail();
            
            $email = EmailModel::findByCode('ET21');
            
            $body = str_replace('{reset_link}', URL::route('user.auth.resetPassword', $user->slug), $email->body);
            $body = str_replace('{user_name}', $user->name, $body);
            
            $data = ['body' => $body];
            
            $info = [ 'reply_name'  => REPLY_NAME,
                      'reply_email' => REPLY_EMAIL,
                      'email'       => $user->email,
                      'name'        => $user->name,
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