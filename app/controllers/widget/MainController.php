<?php namespace Widget;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, SendGrid, URL;

use Job as JobModel;
use Company as CompanyModel;
use User as UserModel;
use Pattern as PatternModel;
use City as CityModel;
use Category as CategoryModel;
use Level as LevelModel;
use Type as TypeModel;
use Language as LanguageModel;
use JobSkill as JobSkillModel;
use JobRecommend as JobRecommendModel;
use UserCollected as UserCollectedModel;
use Apply as ApplyModel;
use Skill as SkillModel;
use FollowCompany as FollowCompanyModel;
use UserFollowCompany as UserFollowCompanyModel;
use UserSkill as UserSkillModel;
use UserLanguage as UserLanguageModel;
use UserEducation as UserEducationModel;
use UserAwards as UserAwardsModel;
use UserExperience as UserExperienceModel;
use UserTestimonial as UserTestimonialModel;
use UserContact as UserContactModel;
use Email as EmailModel;
use CompanyApply as CompanyApplyModel;
use Admin as AdminModel;

class MainController extends \BaseController {

	public function home($slug) {

        $companies = CompanyModel::where('slug', $slug)->get();

        if (count($companies) > 0) {
            $company = $companies[0];
        } else {
            return Redirect::route('user.job.home');
        }


        if (Session::has('user_id')) {
            $param['user'] = UserModel::find(Session::get('user_id'));
        }

        $param['patterns'] = PatternModel::all();
        $param['company'] = $company;

        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::all();
        $param['levels'] = LevelModel::all();
        $param['languages'] = LanguageModel::all();
        $param['types'] = TypeModel::all();


		return View::make('widget.job')->with($param);
	}

    public function login($slug) {

        $companies = CompanyModel::where('slug', $slug)->get();
        if (count($companies) == 0) {
            return Redirect::route('user.job.home');
        }

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        $param['company'] = $companies[0];

        return View::make('widget.login')->with($param);
    }

    public function signup($slug) {
        $companies = CompanyModel::where('slug', $slug)->get();
        if (count($companies) > 0) {
            $company = $companies[0];
        } else {
            return Redirect::route('user.job.home');
        }

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        $user = new UserCollectedModel;

        $user->token = str_random(10);
        $user->city_id = CityModel::whereRaw(true)->min('id');

        $user->save();

        $param['token'] = $user->token;
        $param['company'] = $company;
        $param['cities'] = CityModel::all();
        $param['categories'] = CategoryModel::all();
        $param['levels'] = LevelModel::all();
        $param['languages'] = LanguageModel::all();
        $param['types'] = TypeModel::all();

        return View::make('widget.signup')->with($param);
    }


    public function doLogin($slug) {

        $companies = CompanyModel::where('slug', $slug)->get();
        if (count($companies) > 0) {
            $company = $companies[0];
        } else {
            return Redirect::route('user.job.home');
        }


        $email = Input::get('email');
        $password = Input::get('password');


        $user = UserModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))->get();

        if (count($user) != 0) {

            if ($user[0]->is_active) {
                Session::set('user_id', $user[0]->id);
                return Redirect::route('widget.home', $company->slug);
            }else {
                $alert['msg'] = 'You are not approved yet by admin.';
                $alert['type'] = 'danger';
                return Redirect::route('widget.home', $company->slug)->with('alert', $alert);
            }

        } else {
            $alert['msg'] = 'Email & Password is incorrect';
            $alert['type'] = 'danger';
            return Redirect::route('widget.login', $company->slug)->with('alert', $alert);
        }
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

            $token = Input::get('token');
            UserCollectedModel::where('token', $token)->delete();

            $companyId = Input::get('company_id');

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
            $user->save();

            $alert['msg'] = 'User has been signed up successfully';
            $alert['type'] = 'success';

            $company = CompanyModel::find($companyId);

            return Redirect::route('widget.signup', $company->slug)->with('alert', $alert);
        }
    }

    public function jobView($cSlug, $jSlug) {

        $companies = CompanyModel::where('slug', $cSlug)->get();
        if (count($companies) > 0) {
            $company = $companies[0];
        } else {
            return Redirect::route('user.job.home');
        }

        $job = JobModel::where('slug', $jSlug)->get();
        $id = $job[0]->id;

        $param['job'] = JobModel::find($id);
        $param['jobSkills'] = JobSkillModel::where('job_id', $id)->get();


        if (Session::has('user_id')) {
            $param['userId'] = Session::get('user_id');
            $param['contacts'] = JobRecommendModel::where('user_id', Session::get('user_id'))->groupBy('name')->get();
            $param['patterns'] = PatternModel::where('user_id', Session::get('user_id'))->orWhereNull('user_id')->get();
        }else {
            $param['patterns'] = PatternModel::whereNull('user_id')->get();
        }

        $job = JobModel::find($id);
        $job->views = $job->views + 1;
        $job->save();

        return View::make('widget.jobView')->with($param);
    }

    public function apply($cSlug, $jSlug = NULL) {

        $company = CompanyModel::findBySlug($cSlug);
        if (!$company) {
            return Redirect::route('user.job.home');
        }

        if ($jSlug != NULL) {
            $job = JobModel::findBySlug($jSlug);
            if (!$job) {
                return Redirect::route('user.job.home');
            }
            $param['job'] = $job;
        }



        $param['company'] = $company;
        $param['cities'] = CityModel::where('name', '<>', '')->get();
        $param['categories'] = CategoryModel::all();
        $param['levels'] = LevelModel::all();
        $param['languages'] = LanguageModel::all();
        $param['types'] = TypeModel::all();
        $param['skills'] = SkillModel::all();
        $param['followCompanies'] = FollowCompanyModel::all();
        $param['patterns'] = PatternModel::whereNull('user_id')->get();



        return View::make('widget.apply')->with($param);

    }


    public function doApply($cSlug, $jSlug = NULL) {
        $rules = [
            'name'       => 'required',
            'birthday'   => 'required',
            'year'       => 'numeric',
            'city_id'    => 'required',
            'renumeration_amount' => 'numeric',
            'email'	   => 'required|email',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $password = Input::get('password');
            $email = Input::get('email');

            $user = new UserModel;

            if (UserModel::where('email', $email)->get()->count() > 0) {
                $user = UserModel::where('email', $email)->firstOrFail();
            }

            if ($password !== '') {
                $user->secure_key = md5($user->salt . $password);
            }

            $user->is_active = 1;
            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->gender = Input::get('gender');
            $user->birthday = Input::has('birthday') ? Input::get('birthday') : '';
            $user->year = Input::get('year');
            $user->category_id = Input::get('category_id');
            $user->city_id = Input::get('city_id');
            if (Input::hasFile('profile_image')) {
                $filename = str_random(24) . "." . Input::file('profile_image')->getClientOriginalExtension();
                Input::file('profile_image')->move(ABS_PHOTO_PATH, $filename);
                $user->profile_image = $filename;
            }else {
                $user->profile_image = 'default.png';
            }
            if (Input::hasFile('cover_image')) {
                $filename = str_random(24) . "." . Input::file('cover_image')->getClientOriginalExtension();
                Input::file('cover_image')->move(ABS_PHOTO_PATH, $filename);
                $user->cover_image = $filename;
            }
            $user->about = Input::has('about') ? Input::get('about') : '';
            $user->professional_title = Input::has('professional_title') ? Input::get('professional_title') : '';
            $user->level_id = Input::get('level_id');
            $user->communication_value = Input::has('communication_value') ? Input::get('communication_value') : 0;
            $user->communication_note = Input::has('communication_note') ? Input::get('communication_note') : '';
            $user->organisational_value = Input::get('organisational_value') ? Input::get('organisational_value') : 0;
            $user->organisational_note = Input::has('organisational_note') ? Input::get('organisational_note') : '';
            $user->job_related_value = Input::get('job_related_value') ? Input::get('job_related_value') : 0;
            $user->job_related_note = Input::has('job_related_note') ? Input::get('job_related_note') : '';
            $user->native_language_id = Input::get('native_language_id');
            $user->hobbies = Input::has('hobbies') ? Input::get('hobbies') : '';
            $user->renumeration_amount = Input::has('renumeration_amount') ? Input::get('renumeration_amount') : 0;
            $user->is_freelance = Input::has('is_freelance') ? Input::get('is_freelance') : 0;
            $user->is_parttime = Input::has('is_parttime') ? Input::get('is_parttime') : 0;
            $user->is_fulltime = Input::has('is_fulltime') ? Input::get('is_fulltime') : 0;
            $user->is_internship = Input::has('is_internship') ? Input::get('is_internship') : 0;
            $user->is_volunteer = Input::has('is_volunteer') ? Input::get('is_volunteer') : 0;
            $user->phone = Input::has('phone') ? Input::get('phone') : '';
            $user->address = Input::has('address') ? Input::get('address') : '';
            $user->website = Input::has('website') ? Input::get('website') : '';
            $user->facebook = Input::has('facebook') ? Input::get('facebook') : '';
            $user->linkedin = Input::has('linkedin') ? Input::get('linkedin') : '';
            $user->twitter = Input:: has('twitter') ? Input::get('twitter') : '';
            $user->google = Input:: has('google') ? Input::get('google') : '';
            $user->lat = Input::get('lat');
            $user->lng = Input::get('lng');
            $user->is_finished = Input::get('is_finished');
            $user->is_published = Input::has('is_published') ? Input::get('is_published') : 0;

            $user->save();

            UserSkillModel::where('user_id', $user->id)->delete();

            if (Input::has('skill_name')) {
                $count = 0;

                foreach (Input::get('skill_name') as $sname) {

                    if ($sname == '') break;

                    $skill = new UserSkillModel;

                    $skill->user_id = $user->id;
                    $skill->name = $sname;
                    $skill->value = Input::get('skill_value')[$count];

                    $skill->save();

                    $count++;
                }
            }

            UserLanguageModel::where('user_id', $user->id)->delete();

            if (Input::has('foreign_language_id')) {
                $count = 0;

                foreach (Input::get('foreign_language_id') as $lid) {

                    if ($lid == '') break;

                    $language = new UserLanguageModel;

                    $language->language_id = $lid;
                    $language->user_id = $user->id;
                    $language->understanding = Input::get('understanding')[$count];
                    $language->speaking = Input::get('speaking')[$count];
                    $language->writing = Input::get('writing')[$count];

                    $language->save();

                    $count++;
                }
            }

            UserEducationModel::where('user_id', $user->id)->delete();

            if (Input::has('institution_name')) {
                $count = 0;

                foreach (Input::get('institution_name') as $iname) {

                    if ($iname == '') break;

                    $education = new UserEducationModel;

                    $education->user_id = $user->id;
                    $education->name = $iname;
                    $education->start = Input::get('period_start')[$count];
                    $education->end = Input::get('period_end')[$count];
                    $education->faculty = Input::get('qualification')[$count];
                    $education->notes = Input::get('institution_note')[$count];
                    $education->location = Input::get('location')[$count];

                    $education->save();

                    $count++;

                }
            }

            UserAwardsModel::where('user_id', $user->id)->delete();

            if (Input::has('competition_name')) {
                $count = 0;

                foreach (Input::get('competition_name') as $cname) {

                    if ($cname == '') break;

                    $awards = new UserAwardsModel;

                    $awards->user_id = $user->id;
                    $awards->name = $cname;
                    $awards->prize = Input::get('prize')[$count];
                    $awards->year = Input::get('competition_year')[$count];
                    $awards->location = Input::get('competition_location')[$count];

                    $awards->save();

                    $count++;
                }
            }

            UserExperienceModel::where('user_id', $user->id)->delete();

            if (Input::has('organisation_name')) {
                $count = 0;

                foreach (Input::get('organisation_name') as $oname) {

                    if ($oname == '') break;

                    $experience = new UserExperienceModel;

                    $experience->user_id = $user->id;
                    $experience->name = $oname;
                    $experience->position = Input::get('job_position')[$count];
                    $experience->type_id = Input::get('work_job_type')[$count];
                    $experience->notes = Input::get('work_note')[$count];
                    $experience->start = Input::get('work_period_start')[$count];
                    $experience->end = Input::get('work_period_end')[$count];

                    $experience->save();

                    $count++;
                }

            }

            UserTestimonialModel::where('user_id', $user->id)->delete();

            if (Input::has('testimonial_name')) {
                $count = 0;

                foreach (Input::get('testimonial_name') as $tname) {

                    if ($tname == '') break;

                    $testimonial = new UserTestimonialModel;

                    $testimonial->user_id = $user->id;
                    $testimonial->name = $tname;
                    $testimonial->organisation = Input::get('testimonial_organisation')[$count];
                    $testimonial->notes = Input::get('testimonial_note')[$count];

                    $testimonial->save();

                    $count++;
                }
            }

            $cId = array();
            UserFollowCompanyModel::where('user_id', $user->id)->delete();
            if (Input::has('worked_company_id')) {
                foreach (Input::get('worked_company_id') as $wid) {

                    if ($wid == 0) continue;
                    $ufcompany = new UserFollowCompanyModel;

                    $ufcompany->user_id = $user->id;
                    $ufcompany->follow_company_id = $wid;

                    $ufcompany->save();

                    $fCompany = FollowCompanyModel::find($wid);

                    if (!isset($cId[$fCompany->company_id])) {

                        $cId[$fCompany->company_id] = 1;

                        $email = EmailModel::findByCode('ET19');
                        
                        $body = str_replace('{user_link}', URL::route('user.view', $ufcompany->user->slug), $email->body);
                        
                        $data = ['body' => $body];
                        
                        $info = [ 'reply_name'  => REPLY_NAME,
                                  'reply_email' => REPLY_EMAIL,
                                  'email'       => $fCompany->company->email,
                                  'name'        => $fCompany->company->name,
                                  'subject'     => $email->subject,
                                ];
                        
                        Mail::send('emails.blank', $data, function($message) use($info) {
                            $message->from($info['reply_email'], $info['reply_name']);
                            $message->to($info['email'], $info['name'])
                                    ->subject($info['subject']);
                        });
                    }

                }
            }


            if ($jSlug != NULL)
            {
                $job = JobModel::findBySlug($jSlug);

                $jobId = $job->id;
                $userId = $user->id;
                $name = Input::get('apply_title');
                $description = Input::get('apply_description');

                $apply = new ApplyModel;

                $apply->user_id = $userId;
                $apply->job_id = $jobId;
                $apply->name = $name;
                $apply->description = $description;
                $apply->token = str_random(32);

                if (Input::hasFile('attachFile')) {
                    $filename = str_random(24).".".Input::file('attachFile')->getClientOriginalExtension();
                    Input::file('attachFile')->move(ABS_UPLOAD_PATH, $filename);
                    $apply->attached_file = $filename;
                }

                $apply->save();

                $job = JobModel::find($jobId);
                $cuser = UserModel::find($userId);

                if ($job->is_crawled == 1) {
                    $email = EmailModel::findByCode('ET16');

                    $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', $job->slug), $email->body);
                    $body = str_replace('{user_link}', URL::route('user.view', $cuser->slug), $body);
                    $body = str_replace('{user_name}', $cuser->name, $body);
                    $body = str_replace('{signin_link}', URL::route('company.auth.signup'), $body);
                    $body = str_replace('{account_email}', $job->company->email, $body);
                    $body = str_replace('{account_password}', $job->company->salt, $body);

                    $data = ['body' => $body];

                    $info = [ 'reply_name'  => ($job->name == '') ? $job->company->name : $job->name,
                        'reply_email' => ($job->email == '') ? $job->company->email : $job->email,
                        'email'       => $cuser->email,
                        'name'        => $cuser->name,
                        'subject'     => $email->subject,
                    ];

                    Mail::send('emails.blank', $data, function($message) use($info) {
                        $message->from($info['reply_email'], $info['reply_name']);
                        $message->to($info['email'], $info['name'])
                            ->subject($info['subject']);
                    });

                }else {
                    $rdr = str_replace("\r\n", "<br/>", $description);

                    $email = EmailModel::findByCode('ET25');

                    $body = str_replace('{rdr}', $rdr, $email->body);

                    $sendgrid = new SendGrid($_ENV['SENDGRID_USER'], $_ENV['SENDGRID_USER'], array("turn_off_ssl_verification" => true));
                    $mail = new SendGrid\Email();

                    $receivers = array();

                    if ($job->email != '') {
                        $receivers[] = $job->email;
                    }else {
                        $receivers[] = $job->company->email;
                    }


                    $mail->setTos($receivers)
                        ->setFrom($cuser->email)
                        ->setFromName($cuser->name)
                        ->setSubject($name)
                        ->setHtml($body)
                        ->addCategory($apply->token);

                    $sendgrid->send($mail);
                }
            }
            else
            {
                $company = CompanyModel::findBySlug($cSlug);
                $companyId = $company->id;
                $userId = $user->id;
                $name = Input::get('apply_title');
                $description = Input::get('apply_description');

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

                $email = EmailModel::findByCode('ET25');

                $body = str_replace('{rdr}', $rdr, $email->body);

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
            }

            $alert['msg'] = 'You have been applied successfully.';
            $alert['type'] = 'success';

            return Redirect::route('widget.home', $cSlug);
        }
    }


    public function asyncUserSave() {
        if (!Input::has('token')) {
            return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
        }else {

            $token = Input::get('token');
            $name = Input::has('name') ? Input::get('name') : '';
            $email = Input::has('email') ? Input::get('email' ) : '';
            $city_id = Input::has('city_id') ? Input::get('city_id') : CityModel::whereRaw(true)->min('id');

            $count = UserCollectedModel::where('token', $token)->get()->count();

            if ($count == 0) {
                return Response::json(['result' => 'fail', 'msg' => 'Invalid Request.']);
            }else {

                $count = UserModel::where('email', $email)->get()->count();

                if ($count  == 0) {
                    $user = UserCollectedModel::where('token', $token)->firstOrFail();

                    $user->name = $name;
                    $user->email = $email;
                    $user->city_id = $city_id;

                    $user->save();
                }
            }
            return Response::json(['result' => 'success', 'msg' => 'Your template has been saved successfully.']);
        }
    }


    public function asyncUserLogin() {

        $email = Input::get('email');
        $password = Input::get('password');

        $user = UserModel::whereRaw('email = ? and secure_key = md5(concat(salt, ?))', array($email, $password))->get();

        if (count($user) != 0) {

            if ($user[0]->is_active) {
                Session::set('user_id', $user[0]->id);
                return Response::json(['result' => 'success', 'userId' => $user[0]->id]);
            }else {
                return Response::json(['result' => 'fail', 'msg' => 'You are not approved yet by admin.']);
            }

        } else {
            return Response::json(['result' => 'fail', 'msg' => 'Email & Password is incorrect.']);
        }

    }


    public function asyncUserSignUp() {

        $email = Input::get('email');

        $count = UserModel::where('email', $email)->get()->count();

        if ($count > 0) {
            return Response::json(['result' => 'fail', 'msg' => 'Email is already exist.']);
        }else {

            $user = new UserModel;

            $user->name = Input::get('name');
            $user->email = Input::get('email');
            $user->gender = 0;
            $user->category_id = CategoryModel::whereRaw(true)->min('id');
            $user->city_id = Input::get('city_id');
            $user->level_id = LevelModel::whereRaw(true)->min('id');
            $user->native_language_id = LanguageModel::whereRaw(true)->min('id');
            $user->salt = str_random(8);
            $user->secure_key = md5($user->salt . Input::get('password'));
            $user->profile_image = LOGO;
            $user->is_active = 1;
            $user->save();

            Session::set('user_id', $user->id);

            return Response::json(['result' => 'success', 'userId' => $user->id]);
        }
    }


    public function asyncDoApply() {

        $cSlug = Input::get('companySlug');
        $jSlug = Input::get('jobSlug');
        $name = Input::get('name');
        $description = Input::get('description');
        $email = Input::get('email');
        $gender_id = Input::get('gender');
        $birthday = Input::get('birthday');
        $category_id = Input::get('category_id');
        $year = Input::get('year');
        $city_id = Input::get('city_id');
        $application_title = Input::get('application_title');
        $application_description = Input::get('application_description');

        $count = UserModel::where('email', $email)->get()->count();

        if ($count > 0) {
            $user = UserModel::where('email', $email)->firstOrFail();
        }else {
            $user = new UserModel;
        }

        $user->name = $name;
        $user->email = $email;
        $user->gender = $gender_id;
        $user->category_id = $category_id;
        $user->city_id = $city_id;
        $user->birthday = $birthday;
        $user->year = $year;
        $user->level_id = LevelModel::whereRaw(true)->min('id');
        $user->native_language_id = LanguageModel::whereRaw(true)->min('id');
        $user->salt = str_random(8);
        $user->secure_key = md5($user->salt);
        $user->profile_image = LOGO;
        $user->about = $description;

        $user->save();

        Session::set('user_id', $user->id);

        $job = JobModel::findBySlug($jSlug);

        $jobId = $job->id;
        $userId = $user->id;
        $name = $application_title;
        $description = $application_description;

        $apply = new ApplyModel;

        $apply->user_id = $userId;
        $apply->job_id = $jobId;
        $apply->name = $name;
        $apply->description = $description;
        $apply->token = str_random(32);

        $apply->save();

        $job = JobModel::find($jobId);
        $cuser = UserModel::find($userId);

        if ($job->is_crawled == 1) {
            $email = EmailModel::findByCode('ET16');
            
            $body = str_replace('{job_link}', URL::route('user.dashboard.viewJob', $job->slug), $email->body);
            $body = str_replace('{user_link}', URL::route('user.view', $cuser->slug), $body);
            $body = str_replace('{user_name}', $cuser->name, $body);
            $body = str_replace('{signin_link}', URL::route('company.auth.signup'), $body);
            $body = str_replace('{account_email}', $job->company->email, $body);
            $body = str_replace('{account_password}', $job->company->salt, $body);
            
            $data = ['body' => $body];
            
            $info = [ 'reply_name'  => ($job->name == '') ? $job->company->name : $job->name,
                      'reply_email' => ($job->email == '') ? $job->company->email : $job->email,
                      'email'       => $cuser->email,
                      'name'        => $cuser->name,
                      'subject'     => $email->subject,
                    ];
            
            Mail::send('emails.blank', $data, function($message) use($info) {
                $message->from($info['reply_email'], $info['reply_name']);
                $message->to($info['email'], $info['name'])
                        ->subject($info['subject']);
            });
        }else {
            $rdr = str_replace("\r\n", "<br/>", $description);
            
            $email = EmailModel::findByCode('ET25');
            
            $body = str_replace('{rdr}', $rdr, $email->body);
            
            $sendgrid = new SendGrid($_ENV['SENDGRID_USER'], $_ENV['SENDGRID_USER'], array("turn_off_ssl_verification" => true));
            $mail = new SendGrid\Email();

            $receivers = array();

            if ($job->email != '') {
                $receivers[] = $job->email;
            }else {
                $receivers[] = $job->company->email;
            }


            $mail->setTos($receivers)
                ->setFrom($cuser->email)
                ->setFromName($cuser->name)
                ->setSubject($name)
                ->setHtml($body)
                ->addCategory($apply->token);

            $sendgrid->send($mail);
        }

        return Response::json(['result' => 'success', 'msg' => 'You have been successfully apply']);
    }


    public function asyncCheckUser() {
        $email = Input::get('email');
        $jobId = Input::get('job_id');
        $companyId = Input::get('company_id');


        if (UserModel::where('email', $email)->get()->count() > 0) {

            $user = UserModel::where('email', $email)->firstOrFail();
            $param['user'] = $user;
            $param['cities'] = CityModel::where('name', '<>', '')->get();
            $param['categories'] = CategoryModel::all();
            $param['levels'] = LevelModel::all();
            $param['languages'] = LanguageModel::all();
            $param['types'] = TypeModel::all();
            $param['skills'] = SkillModel::all();
            $param['followCompanies'] = FollowCompanyModel::all();
            $param['patterns'] = PatternModel::whereNull('user_id')->get();
            $param['userSkills']  = UserSkillModel::where('user_id', Session::get('user_id'))->get();
            $param['userLanguages'] = UserLanguageModel::where('user_id', Session::get('user_id'))->get();
            $param['userEducations'] = UserEducationModel::where('user_id', Session::get('user_id'))->get();
            $param['userAwards'] = UserAwardsModel::where('user_id', Session::get('user_id'))->get();
            $param['userExperiences'] = UserExperienceModel::where('user_id', Session::get('user_id'))->get();
            $param['userTestimonials'] = UserTestimonialModel::where('user_id', Session::get('user_id'))->get();
            $param['userCompanies'] = UserFollowCompanyModel::where('user_id', Session::get('user_id'))->get();

            $applyFlag = 0;
            if ($jobId != '') {
                if (ApplyModel::where('user_id', $user->id)->where('job_id', $jobId)->get()->count() > 0) {
                    $applyFlag = 1;
                }
            }else {
                if (CompanyApplyModel::where('user_id', $user->id)->where('company_id', $companyId)->get()->count() > 0) {
                    $applyFlag = 1;
                }
            }

            return Response::json(['result' => 'success', 'formView' => View::make('widget.ajaxApply')->with($param)->__toString(), 'applyFlag' =>  $applyFlag]);

        }else {
            return Response::json(['result' => 'fail']);
        }
    }
}
