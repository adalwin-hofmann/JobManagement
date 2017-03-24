<?php namespace User;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response, Mail, SendGrid, URL, DB, Queue;
use Admin as AdminModel;
use Job as JobModel;
use User as UserModel;
use Category as CategoryModel;
use Company as CompanyModel;
use JobSkill as JobSkillModel;
use Type as TypeModel;
use Apply as ApplyModel;
use Cart as CartModel;
use JobRecommend as JobRecommendModel;
use Pattern as PatternModel;
use City as CityModel;
use UserMessage as UserMessageModel;
use UserContact as UserContactModel;
use Message as MessageModel;
use Email as EmailModel;

class JobController extends \BaseController {
	
	public function home($id = 0) {
        if (Session::has('company_id')) {
            return Redirect::route('company.dashboard');
        }else {
            $param['pageNo'] = 0;
            if ($alert = Session::get('alert')) {
                $param['alert'] = $alert;
            }

            $param['categories'] = CategoryModel::calculateCount()->orderBy('cnt', 'DESC')->take(12)->get();

            if (Session::has('company_id')) {
                $param['company'] = CompanyModel::find(Session::get('company_id'));
            }

            if (Session::has('user_id')) {
                $param['user'] = UserModel::find(Session::get('user_id'));
            }

            if (Session::has('agency_id')) {
                $param['agency'] = CompanyModel::find(Session::get('agency_id'));
            }

            $param['cities'] = CityModel::where('name', '<>', '')->get();

            $param['jobs'] = JobModel::where('bonus', '<>', 0)->orderBy(DB::raw('rand()'))->take(8)->get();

            return View::make('user.job.home')->with($param);
        }
	}
	
	public function viewJob($slug, $company_id = '') {
		
		$job = JobModel::findBySlug($slug);
		$id = $job->id;
        if (!$job) {
            return View::make('404.index');
        }
		
		$param['job'] = JobModel::find($id);
		$param['jobSkills'] = JobSkillModel::where('job_id', $id)->get();

		
		if (Session::has('user_id')) {
			$param['userId'] = Session::get('user_id');
			$param['contacts'] = UserContactModel::where('user_id', Session::get('user_id'))->get();
			$param['patterns'] = PatternModel::where('user_id', Session::get('user_id'))->orWhereNull('user_id')->get();
            $param['user'] = UserModel::find(Session::get('user_id'));
		}else {
			$param['patterns'] = PatternModel::whereNull('user_id')->get();
		}
		
		$job = JobModel::find($id);
		$job->views = $job->views + 1;
		$job->save();		
		
		if ($company_id != '') {
		    $company = CompanyModel::find($company_id);
		    if ($company->is_active) {
		        Session::set('company_is_admin', $company->is_admin);
		        Session::set('company_id', $company->id);
		        
		        return Redirect::route('company.job.view', $job->slug);		        
		    } else {
		        $param['company'] = $company;
		    }
		}

        if (Session::has('company_id')) {
            $param['company'] = CompanyModel::find(Session::get('company_id'));
        }


		return View::make('user.job.view')->with($param);
	}


    public function viewJobForApply($slug, $userId, $code) {

        $job = JobModel::where('slug', '=', $slug)->get();
        $id = $job[0]->id;

        $param['job'] = JobModel::find($id);
        $param['jobSkills'] = JobSkillModel::where('job_id', $id)->get();

        $user = UserModel::find($userId);

        if ($user->salt == $code) {
            Session::set('user_id', $user->id);

            if (Session::has('user_id')) {
                $param['userId'] = Session::get('user_id');
                $param['contacts'] = UserContactModel::where('user_id', Session::get('user_id'))->get();
                $param['patterns'] = PatternModel::where('user_id', Session::get('user_id'))->orWhereNull('user_id')->get();
            }else {
                $param['patterns'] = PatternModel::whereNull('user_id')->get();
            }

            $param['user'] = $user;

            $job = JobModel::find($id);
            $job->views = $job->views + 1;
            $job->save();


            return View::make('user.job.view')->with($param);
        }else {
            return View::make('404.index');
        }
    }
	
	public function search() {
		$param['pageNo'] = 1;

        $param['keyword'] = Input::has('keyword') ? Input::get('keyword') : '';
		$param['category_id'] = Input::has('category_id') ? Input::get('category_id') : '';
		$param['sub_category_id'] = Input::has('sub_category_id') ? Input::get('sub_category_id') : '';
		$param['type_id'] = Input::has('type_id') ? Input::get('type_id') : '';
		$param['budget_min'] = Input::has('min') ? Input::get('min') : BUDGET_MIN;
		$param['budget_max'] = Input::has('max') ? Input::get('max') : BUDGET_MAX;
	    $param['period'] = Input::has('period') ? Input::get('period') : 0;
        $param['city_id'] = Input::has('city_id') ? Input::get('city_id') : 0;
        $param['cities'] = CityModel::where('name', '<>', '')->get();

        $category_id = $param['category_id'];
        if ($param['sub_category_id'] != '') {
            $category_id = $param['sub_category_id'];
        }

		$result = JobModel::whereRaw(true);
	
		if ($category_id != '') {
			$result = $result->where('category_id', '=', $category_id);

            $categories = CategoryModel::where('id', $category_id)->orWhere('parent_id', $category_id)->get();
            foreach ($categories as $category) {
                $result = $result->orWhere('category_id', $category->id);
            }
		}
		
		if ($param['keyword'] != '') {

            $keyIds = array();
            $keyIds[] = 0;

            $jobs = JobModel::where('name', 'like', '%'.$param['keyword'].'%')->get();
            foreach($jobs as $job) {
                $keyIds[] = $job->id;
            }

            $companies = CompanyModel::where('name', 'like', '%'.$param['keyword'].'%')->get();
            foreach($companies as $company) {
                $cJobs = JobModel::where('company_id', $company->id)->get();
                foreach($cJobs as $cJob) {
                    $keyIds[] = $cJob->id;
                }
            }

            $skills = JobSkillModel::where('name', 'like', '%'.$param['keyword'].'%')->get();
            foreach ($skills as $skill) {
                $keyIds[] = $skill->job_id;
            }

            $result = $result->whereIn('id', $keyIds);
		}
		
		
		if ($param['type_id'] != '') {
			$result = $result->where('type_id', '=', $param['type_id']);
		}


		if ($param['period'] != 0) {
            if ($param['period'] == 1) {
                $created_at = date('Y-m-d');
            }else {
                $created_at = date('Y-m-d', strtotime('-'.$param['period'].' days'));
            }
            $result = $result->where('created_at', '>=', $created_at." 00:00:00");
		}

        if ($param['city_id'] != 0) {
            $result = $result->where('city_id', $param['city_id']);
        }
		
		if ($param['budget_min'] != '') {
			$result = $result->where('salary', '>=', $param['budget_min']);
		}
		
		if ($param['budget_max'] != '') {
			$result = $result->where('salary', '<=', $param['budget_max']);
		}

        $result = $result->where('is_finished', 1)->where('is_active', 1);

	
		if (Session::has('user_id')) {
			$param['user'] = UserModel::find(Session::get('user_id'));
			$param['contacts'] = UserContactModel::where('user_id', Session::get('user_id'))->get();
			$param['patterns'] = PatternModel::where('user_id', Session::get('user_id'))->orWhereNull('user_id')->get();
		}else {
			$param['patterns'] = PatternModel::whereNull('user_id')->get();
		}
		
		$jobs = $result->paginate(PAGINATION_SIZE);
		$param['jobs'] = $jobs;
		$param['categories'] = CategoryModel::where('parent_id', NULL)->get();
		$param['types'] = TypeModel::all();
		
		
	
		return View::make('user.job.search')->with($param);
	}

    public function doApplyJob() {
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

            $jobId = Input::get('job_id');
            $userId = Session::get('user_id');
            $name = Input::get('name');
            $description = Input::get('description');

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

            $admin = AdminModel::whereRaw(true)->firstOrFail();
            $prevLevel = (int) ($cuser->score / $admin->level_score);
            $cuser->score = $cuser->score + $admin->apply_score;
            $currLevel = (int) ($cuser->score / $admin->level_score);

            if ($prevLevel != $currLevel) {
                $cuser->fb_share = 1;
            }

            $cuser->save();

            if ($job->is_crawled == 1) {
                if ($job->email != '') {
                    $user = array(
                        'email'=> $job->email,
                        'username'=>$job->name,
                        'reply_email'=> $cuser->email,
                        'reply_name'=> $cuser->name,
                    );
                }else {
                    $user = array(
                        'email'=> $job->company->email,
                        'username'=>$job->company->name,
                        'reply_email'=> $cuser->email,
                        'reply_name'=> $cuser->name,
                    );
                }


                Mail::send('user.mails.applyCrawledJob', array('job_link' => HTTP_PATH.'job/'.$job->slug, 'user_link' => HTTP_PATH.'user/'.$cuser->slug, 'user_name' => $cuser->name, 'signin_link' => 'http://socialheadhunter.org/company/login', 'account_email' => $job->company->email, 'account_password' => $job->company->salt, 'site_link' => HTTP_PATH), function($message) use($user)
                {
                    $message->to($user['email'], $user['username'])->from($user['reply_email'], $user['reply_name'])->subject('SocialHeadHunter')->replyTo($user['reply_email']);
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

            return Redirect::back();
        }
    }

	public function verifyHint($slug, $id) {

        $job = JobModel::where('slug', $slug)->get();
        $hint = JobRecommendModel::find($id);

        if ($hint->job_id != $job[0]->id) {
            $alert['msg'] = 'Verify links is wrong.';
            $alert['type'] = 'danger';
            $param['alert'] = $alert;

        }

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        $user = UserModel::find($hint->user->id);

        $admin = AdminModel::whereRaw(true)->firstOrFail();

        $prevLevel = (int) ($user->score / $admin->level_score);
        $user->score = $user->score + $admin->recruit_verify_score;
        $currLevel = (int) ($user->score / $admin->level_score);

        if ($prevLevel != $currLevel) {
            $user->fb_share = 1;
        }

        $user->save();


        $param['jobId'] = $job[0]->id;
        $param['hintId'] = $hint->id;

        if (Session::has('user_id')) {
            $param['user'] = UserModel::find(Session::get('user_id'));
        }elseif (Session::has('company_id')) {
            $param['company'] = UserModel::find(Session::get('company_id'));
        }


        return View::make('user.job.verifyHint')->with($param);
    }

    public function doVerifyHint() {
        $jobId = Input::get('job_id');
        $hintId = Input::get('hint_id');
        $verifyCode = Input::get('verify_code');

        $job = JobModel::find($jobId);

        $hint = JobRecommendModel::where('id', $hintId)->where('job_id', $jobId)->where('verifyCode', $verifyCode)->get();

        if (count($hint) != 0) {

            $recommend = JobRecommendModel::find($hintId);

            $recommend->is_verified = 1;

            $recommend->save();

            $alert['msg'] = 'Successfully verified.';
            $alert['type'] = 'success';
            return Redirect::route('user.job.verifyHint', array($job->slug, $hintId))->with('alert', $alert);

        } else {
            $alert['msg'] = 'Verification Code is Wrong!!!';
            $alert['type'] = 'danger';
            return Redirect::route('user.job.verifyHint', array($job->slug, $hintId))->with('alert', $alert);
        }
    }
	
	/* Functions for ajax */
	public function asyncCheckApply() {
		if (Session::has('user_id')) {
			if (Input::has('job_id')) {
				$jobId = Input::get('job_id');
				$userId = Session::get('user_id');
				$status = ApplyModel::where('job_id', $jobId)->where('user_id', $userId)->count();
	
				if ($status == 0) {
					return Response::json(['result' => 'success', 'msg' => 'You have been successfully apply']);
				} else {
					return Response::json(['result' => 'failed', 'msg' => 'You have already apply to this job', 'code' => 'CD00']);
				}
			} else {
				return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', 'code' => 'CD00']);
			}
		} else {
			return Response::json(['result' => 'failed', 'msg' => 'You must login for apply', 'code' => 'CD01']);
		}
	}
	
	public function asyncApply() {
		if (Input::has('job_id')) {
			$jobId = Input::get('job_id');
			$userId = Session::get('user_id');
			$name = Input::get('name');
			$description = Input::get('description');
	
			$apply = new ApplyModel;
				
			$apply->user_id = $userId;
			$apply->job_id = $jobId;
			$apply->name = $name;
			$apply->description = $description;

			$apply->save();
				
			CartModel::where('user_id', $userId)->where('job_id', $jobId)->delete();
				
			return Response::json(['result' => 'success', 'msg' => 'You have been successfully apply.']);
		} else {
			return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', 'code' => 'CD00']);
		}
	}
	
	public function asyncAddToCart() {
		if (Session::has('user_id')) {
			if (Input::has('job_id')) {
				$jobId = Input::get('job_id');
				$userId = Session::get('user_id');
				$status_cart = CartModel::where('job_id', $jobId)->where('user_id', $userId)->count();
				$status_apply = ApplyModel::where('job_id', $jobId)->where('user_id', $userId)->count();
	
				if ($status_cart != 0) {
					return Response::json(['result' => 'failed', 'msg' => 'This job is already added to application cart', 'code' => 'CD00']);
				}else if ($status_apply != 0) {
					return Response::json(['result' => 'failed', 'msg' => 'You have already apply to this job', 'code' => 'CD00']);
				}else {
					$cart = new CartModel;
	
					$cart->user_id = $userId;
					$cart->job_id = $jobId;
	
					$cart->save();
	
					return Response::json(['result' => 'success', 'msg' => 'This job have been successfully added to application cart']);
				}
			} else {
				return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', 'code' => 'CD00']);
			}
		} else {
			return Response::json(['result' => 'failed', 'msg' => 'You must login for add to application cart', 'code' => 'CD01']);
		}
	}
	
	public function asyncRemoveFromCart() {
		if (Session::has('user_id')) {
			if (Input::has('cart_id')) {
				$cartId = Input::get('cart_id');
				CartModel::find($cartId)->delete();
		
				return Response::json(['result' => 'success', 'msg' => 'This job have been removed from your application cart']);
			} else {
				return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', 'code' => 'CD00']);
			}
		} else {
			return Response::json(['result' => 'failed', 'msg' => 'You must login for remove job', 'code' => 'CD01']);
		}		
	}
	
	public function asyncAddHint() {
		if (Session::has('user_id')) {
			if (Input::has('job_id')) {
				$jobId = Input::get('job_id');
				$userId = Session::get('user_id');
				$name = Input::has('name') ? Input::get('name') : '';
				$phonenumber = Input::has('phonenumber') ? Input::get('phonenumber') : '';
				$email = Input::has('email') ? Input::get('email') : '';
				$currentJob = Input::has('currentJob') ? Input::get('currentJob') : '';
				$previousJobs = Input::has('previousJobs') ? Input::get('previousJobs') : '';
				$description = Input::has('description') ? Input::get('description') : '';

                $job = JobModel::find($jobId);
                $cuser = UserModel::find($userId);
	
				$recommend = new JobRecommendModel;
	
				$recommend->user_id = $userId;
				$recommend->job_id = $jobId;
				$recommend->name = $name;
				$recommend->phone = $phonenumber;
				$recommend->email = $email;
				$recommend->currentJob = $currentJob;
				$recommend->previousJobs = $previousJobs;
				$recommend->description = $description;

                $admin = AdminModel::whereRaw(true)->firstOrFail();

                $admin = AdminModel::whereRaw(true)->firstOrFail();
                $prevLevel = (int) ($cuser->score / $admin->level_score);
                $cuser->score = $cuser->score + $admin->recruit_score;
                $currLevel = (int) ($cuser->score / $admin->level_score);

                if ($prevLevel != $currLevel) {
                    $cuser->fb_share = 1;
                }

                $cuser->save();

                $count = UserContactModel::where('user_id', $userId)->where('email', $email)->get()->count();

                if ($count == 0) {
                    $contact = new UserContactModel;

                    $contact->user_id = $userId;
                    $contact->name = $name;
                    $contact->email = $email;
                    $contact->phone = $phonenumber;
                    $contact->save();
                }

                if ($job->is_verified == 0) {
                    $recommend->is_verified = 1;
                    $recommend->verifyCode = '';

                    $recommend->save();
                }else {
                    $recommend->is_verified = 0;
                    $recommend->verifyCode = str_random(12);

                    $recommend->save();

                    Queue::push('\SH\Queue\UserVerifyHintMessage', ['user_id' => $userId, 'job_id' => $jobId, 'recommend_id' => $recommend->id] );
                }

				return Response::json(['result' => 'success', 'msg' => 'Your hint was submitted successfully.', 'score' => $cuser->score, 'levelScore' => $admin->level_score]);
			} else {
				return Response::json(['result' => 'failed', 'msg' => 'Invalid Request', 'code' => 'CD00']);
			}
		} else {
			return Response::json(['result' => 'failed', 'msg' => 'You must login for add to application cart', 'code' => 'CD01']);
		}
	}
	
	
	public function asyncSendMessage() {		
		if (Session::has('user_id')) {

            if (Input::get('job_id') == NULL) {
                $message_data = Input::get('message');

                $company = CompanyModel::find(Input::get('company_id'));
                $user = UserModel::find(Session::get('user_id'));

                Queue::push('\SH\Queue\UserMessageToCompany', ['user_id' => Session::get('user_id'), 'company_id' => $company->id, 'message_data' => $message_data] );
            }else {
                $job = JobModel::find(Input::get('job_id'));
                $message_data = Input::get('message');

                $company = CompanyModel::find($job->company_id);
                $user = UserModel::find(Session::get('user_id'));

                Queue::push('\SH\Queue\UserMessage', ['user_id' => Session::get('user_id'), 'job_id' => $job->id, 'company_id' => $company->id, 'message_data' => $message_data] );
            }

		    
	        $message = new MessageModel;
            if (Input::get('job_id') != NULL) {
                $message->job_id = $job->id;
            }
	        $message->user_id = $user->id;
	        $message->company_id = $company->id;
	        $message->description = $message_data;
	        $message->is_company_sent = FALSE;
	        $message->is_read = FALSE;
	        $message->save();
	        
	        return Response::json(['result' => 'success', 'msg' => 'You have sent message successfully.']);
	        
		} else {
			return Response::json(['result' => 'failed', 'msg' => 'You must login for this', 'code' => 'CD01']);
		}
	}
	
}
