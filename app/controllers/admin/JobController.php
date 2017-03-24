<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Request, Response;
use Job as JobModel;
use Category as CategoryModel;
use Company as CompanyModel;
use Level as LevelModel;
use City as CityModel;
use Language as LanguageModel;
use Type as TypeModel;
use Presence as PresenceModel;
use Benefits as BenefitsModel;
use JobSkill as JobSkillModel;
use JobLanguage as JobLanguageModel;

class JobController extends \BaseController {
    
	public function newJobs($id = 0) {


        $created_at = date('Y-m-d');

        if ($id == 0) {
            $param['jobs'] = JobModel::where('is_crawled', 1)->where('is_active', 0)->where('id_crawled', 'like', '1-%')->orderBy('created_at', 'DESC')->paginate(10);
        }elseif ($id == 1 ) {
            $param['jobs'] = JobModel::where('is_crawled', 1)->where('is_active', 0)->where('id_crawled', 'like', '2-%')->orderBy('created_at', 'DESC')->paginate(10);
        }elseif ($id == 2) {
            $param['jobs'] = JobModel::where('is_crawled', 1)->where('is_active', 0)->where('id_crawled', 'like', '3-%')->orderBy('created_at', 'DESC')->paginate(10);
        }elseif ($id == 3) {
            $param['jobs'] = JobModel::where('is_crawled', 1)->where('is_active', 0)->where('id_crawled', 'like', '4-%')->orderBy('created_at', 'DESC')->paginate(10);
        }elseif ($id == 4) {
            $param['jobs'] = JobModel::where('is_crawled', 1)->where('is_active', 0)->where('id_crawled', 'like', '5-%')->orderBy('created_at', 'DESC')->paginate(10);
        }


        $param['pageNo'] = 23;
        $param['sid'] = $id;

	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.newJobs.index')->with($param);
	}

    public function index() {
        $param['jobs'] = JobModel::paginate(10);
        $param['pageNo'] = 11;

        if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }

        return View::make('admin.job.index')->with($param);
    }
	
	public function create() {
		$param['companies'] = CompanyModel::all();
		$param['categories'] = CategoryModel::all();
		$param['presences'] = PresenceModel::all();
		$param['cities'] = CityModel::all();
		$param['languages'] = LanguageModel::all();
		$param['types'] = TypeModel::all();
		$param['levels'] = LevelModel::all();
		
		$param['pageNo'] = 11;
		
	    return View::make('admin.job.create')->with($param);
	}
	
	public function edit($id) {
	    $param['job'] = JobModel::find($id);
		$param['companies'] = CompanyModel::all();
		$param['categories'] = CategoryModel::all();
		$param['presences'] = PresenceModel::all();
		$param['cities'] = CityModel::all();
		$param['languages'] = LanguageModel::all();
		$param['types'] = TypeModel::all();
		$param['levels'] = LevelModel::all();
		$param['job_benefits'] = BenefitsModel::where('job_id', $id)->get();
		$param['job_skills'] = JobSkillModel::where('job_id', $id)->get();
		$param['job_languages'] = JobLanguageModel::where('job_id', $id)->get();
		$param['pageNo'] = 11;
	    
	    return View::make('admin.job.edit')->with($param);
	}

    public function newJobsEdit($id) {
        $param['job'] = JobModel::find($id);
        $param['companies'] = CompanyModel::all();
        $param['categories'] = CategoryModel::all();
        $param['presences'] = PresenceModel::all();
        $param['cities'] = CityModel::all();
        $param['languages'] = LanguageModel::all();
        $param['types'] = TypeModel::all();
        $param['levels'] = LevelModel::all();
        $param['job_benefits'] = BenefitsModel::where('job_id', $id)->get();
        $param['job_skills'] = JobSkillModel::where('job_id', $id)->get();
        $param['job_languages'] = JobLanguageModel::where('job_id', $id)->get();
        $param['pageNo'] = 23;

        return View::make('admin.newJobs.edit')->with($param);
    }

    public function newJobsActive() {
        $created_at = date('Y-m-d');
        $jobs  = JobModel::where('created_at', '>=', $created_at." 00:00:00")->get();

        foreach ($jobs as $job) {
            $job->is_active = 1;

            $job->save();
        }

        return Redirect::back();
    }
	
	public function store() {
	    
        $rules = ['name' => 'required',
                  'email' => 'required|email',
                  'phone' => 'required',
				  'description' => 'required',
                  'year' => 'numeric',
                  'salary' => 'required|numeric'
                 ];
        
	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
	    	
	    	$jobId = 0;
	    	$is_published = 0;
	    	$is_name = 0;
	    	$is_phonenumber = 0;
	    	$is_email = 0;
	    	$is_currentjob = 0;
	    	$is_previousjobs = 0;
	    	$is_description = 0;
            $is_verified = 0;
	    	
	    	if (Input::has('is_published')) {
	    		$is_published = Input::get('is_published');
	    	}
	    	
	    	if (Input::has('is_name')) {
	    		$is_name = Input::get('is_name');
	    	}	    	
	    	
	    	if (Input::has('is_phonenumber')) {
	    		$is_phonenumber = Input::get('is_phonenumber');
	    	}
	    	
	    	if (Input::has('is_email')) {
	    		$is_email = Input::get('is_email');
	    	}
	    	
	    	if (Input::has('is_currentjob')) {
	    		$is_currentjob = Input::get('is_currentjob');
	    	}
	    	
	    	if (Input::has('is_previousjobs')) {
	    		$is_previousjobs = Input::get('is_previousjobs');
	    	}
	    	
	    	if (Input::has('is_description')) {
	    		$is_description = Input::get('is_description');
	    	}

            if (Input::has('is_verified')) {
                $is_verified = Input::get('is_verified');
            }

            
            if (Input::has('job_id')) {
                $id = Input::get('job_id');
                $job = JobModel::find($id);
                $is_active = Input::get('is_active');
                $jobId = $id;
 
                $job->is_active = $is_active;
            } else {
                $job = new JobModel;                
            } 

            $job->company_id = Input::get('company_id');
            $job->name = Input::get('name');
            $job->level_id = Input::get('level_id');
            $job->description = Input::get('description');
            $job->category_id = Input::get('category_id');
            $job->presence_id = Input::get('presence_id');
            $job->year = Input::get('year');
            $job->city_id = Input::get('city_id');
            $job->native_language_id = Input::get('native_language_id');
            $job->requirements = Input::get('requirements');
            $job->is_name = $is_name;
            $job->is_phonenumber = $is_phonenumber;
            $job->is_email = $is_email;
            $job->is_currentjob = $is_currentjob;
            $job->is_previousjobs = $is_previousjobs;
            $job->is_description = $is_description;
            $job->is_verified = $is_verified;
            $job->bonus = Input::get('bonus');
            $job->type_id = Input::get('type_id');
            $job->salary = Input::get('salary');
            $job->email = Input::get('email');
            $job->phone = Input::get('phone');
            $job->lat = Input::get('lat');
            $job->long = Input::get('lng');
            $job->is_finished = 1;
            $job->salary = Input::get('salary');
            $job->paid_after = Input::get('paid_after');
            $job->bonus_description = Input::get('bonus_description');
            $job->link_address = Input::get('link_address');
                       
            $job->save();
            
            if ($jobId == 0) $jobId = $job->id;
            
            
            //save Benefit Names
            
            if (Input::has('benefit_name')) {
	            BenefitsModel::where('job_id', $jobId)->delete();
	            
	            foreach (Input::get('benefit_name') as $bname) {
	            	
	            	$benefits = new BenefitsModel;
	            	
	            	$benefits->job_id = $jobId;
	            	$benefits->name = $bname;
	            	 
	            	$benefits->save();
	            }
            }
            
            //save Job Skills
            if (Input::has('skill_name')) {
	            $count = 0;
	            JobSkillModel::where('job_id', $jobId)->delete();
	            
	            foreach (Input::get('skill_name') as $sname) {
	            	 
	            	$jobskill = new JobSkillModel;
	            	 
	            	$jobskill->job_id = $jobId;
	            	$jobskill->name = $sname;
	            	$jobskill->value = Input::get('skill_value')[$count];
	            
	            	$jobskill->save();
	            	
	            	$count ++;
	            }
            }

            
            //save Job Foreign Language
            if (Input::has('foreign_language_id')) {
	            $count = 0;
	            JobLanguageModel::where('job_id', $jobId)->delete();
	            
	            foreach (Input::get('foreign_language_id') as $lid) {
	            
	            	$joblanguage = new JobLanguageModel;
	            
	            	$joblanguage->job_id = $jobId;
	            	$joblanguage->language_id = $lid;
	            	$joblanguage->understanding = Input::get('understanding')[$count];
	            	$joblanguage->speaking = Input::get('speaking')[$count];
	            	$joblanguage->writing = Input::get('writting')[$count];
	            	$joblanguage->name = '';
	            
	            	$joblanguage->save();
	            	 
	            	$count ++;
	            } 
            }          

            
            $alert['msg'] = 'Job has been saved successfully';
            $alert['type'] = 'success';

            $url = Input::get("requestURL");

            if (strpos($url, 'news') !== false) {
                return Redirect::route('admin.job.news')->with('alert', $alert);
            }

            return Redirect::route('admin.job')->with('alert', $alert);
	    }
	}
	
	public function delete($id) {
	    CompanyModel::find($id)->delete();
	    
	    $alert['msg'] = 'Job has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.job')->with('alert', $alert);
	}


    //ajax functions

    public function asyncUpdateJobStatus() {
        $jobId = Input::get('job_id');

        $job = JobModel::find($jobId);

        $job->is_active = 1 - $job->is_active;

        $job->save();

        return Response::json(['result' => 'success', 'activeStatus' => $job->is_active,  'msg' => 'Job updated successfully.']);
    }


    public function asyncUpdateJobContact() {
        $jobId = Input::get('job_id');
        $email = Input::get('email');

        $job = JobModel::find($jobId);

        $job->email = $email;

        $job->save();

        return Response::json(['result' => 'success', 'activeStatus' => $job->is_active,  'msg' => 'Job updated successfully.']);
    }

    public function asyncUpdateJobLink() {
        $jobId = Input::get('job_id');
        $link = Input::get('link');

        $job = JobModel::find($jobId);

        $job->link_address = $link;

        $job->save();

        return Response::json(['result' => 'success', 'activeStatus' => $job->is_active,  'msg' => 'Job updated successfully.']);
    }
}
