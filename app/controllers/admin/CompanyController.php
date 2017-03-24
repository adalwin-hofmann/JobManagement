<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use Company as CompanyModel;
use City as CityModel;
use Category as CategoryModel;
use Teamsize as TeamsizeModel;
use Service as ServiceModel;
use CompanyService as CompanyserviceModel;
use FollowCompany as FollowCompanyModel;

class CompanyController extends \BaseController {
    
	public function index() {
        $param['companies'] = CompanyModel::paginate(10);
        $param['pageNo'] = 10;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.company.index')->with($param);
	}
	
	public function create() {
		$param['cities'] = CityModel::all();
		$param['teamsizes'] = TeamsizeModel::all();
		$param['categories'] = CategoryModel::all();
		$param['services']  = ServiceModel::all();
		$param['pageNo'] = 10;
		
	    return View::make('admin.company.create')->with($param);
	}
	
	public function edit($id) {
	    $param['company'] = CompanyModel::find($id);
		$param['cities'] = CityModel::all();
		$param['teamsizes'] = TeamsizeModel::all();
		$param['categories'] = CategoryModel::all();
		$param['services'] = ServiceModel::all();
		$param['companyServices']  = CompanyServiceModel::where('company_id', $id)->get();
	    $param['pageNo'] = 10;
	    
	    return View::make('admin.company.edit')->with($param);
	}
	
	public function store() {
		
		$rules = [
			'name' 		=> 'required',
			'password' 	=> 'required',
			'email' 		=> 'required|email',
			'year' 		=> 'numeric',
		];
		
		if (Input::has('company_id')) {
			$rules = [
				'name' 		=> 'required',
				'email' 		=> 'required|email',
				'year' 		=> 'numeric',
			];	
		}
        
	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
            $password = Input::get('password');
            $companyId = 0;
            
            $is_published = 0;
            
            if (Input::has('is_published')) {
            	$is_published = Input::get('is_published');
            }
            
            if (Input::has('company_id')) {
                $id = Input::get('company_id');
                $company = CompanyModel::find($id);
                $is_active = Input::get('is_active');
                
                if ($password !== '') {
                    $company->secure_key = md5($company->salt.$password);
                }
                $company->is_active = $is_active;
                
                $companyId = $id;
                
            } else {
                $company = new CompanyModel;
                
                if ($password === '') {
                    $alert['msg'] = 'You have to enter password';
                    $alert['type'] = 'danger';
                    return Redirect::route('admin.company.create')->with('alert', $alert);
                }
                $company->salt = str_random(8);
                $company->secure_key = md5($company->salt.$password);                
            }       
            
            $company->name 			= Input::get('name');
            $company->tag 			= Input::get('tag');
            $company->year 			= Input::get('year');
            $company->teamsize_id 	= Input::get('teamsize_id');
            $company->category_id 	= Input::get('category_id');
            $company->city_id		= Input::get('city_id');
            $company->description 	= Input::get('description');
            $company->expertise 	= Input::get('expertise');
            $company->address 		= Input::get('address');
            $company->email 		= Input::get('email');
            $company->phone 		= Input::get('phone');
            $company->website 		= Input::get('website');
            $company->facebook 		= Input::get('facebook');
            $company->linkedin 		= Input::get('linkedin');
            $company->twitter		= Input::get('twitter');
            $company->google 		= Input::get('google');
			$company->lat 			= Input::get('lat');
			$company->long			= Input::get('lng');
			$company->is_published  = $is_published;
			$company->is_finished 	= 1;
            
            if (Input::hasFile('logo')) {
            	$filename = str_random(24).".".Input::file('logo')->getClientOriginalExtension();
            	Input::file('logo')->move(ABS_LOGO_PATH, $filename);
            	$company->logo = $filename;
            }
            
            $company->save();
            
            if ($companyId == 0) $companyId = $company->id; 

            if (Input::has('service_id')) {
	            $count = 0;
	            
	            CompanyserviceModel::where('company_id', $companyId)->delete();
	
	            foreach (Input::get('service_id') as $sId) {
	            	$companyService = new CompanyserviceModel;
	            	
	            	$companyService->company_id = $companyId;
	            	$companyService->service_id = $sId;
	            	$companyService->description = Input::get('service_description')[$count];
	            	
	            	$companyService->save();
	            	$count ++;
	            }
            }

            FollowCompanyModel::where('company_id', $company->id)->delete();
            if (Input::has('follow_company_name')) {
                foreach (Input::get('follow_company_name') as $fname) {
                    $fCompany = new FollowCompanyModel;

                    $fCompany->company_id = $company->id;
                    $fCompany->name = $fname;
                    $fCompany->status = $company->is_active;

                    $fCompany->save();
                }
            }
            
            $alert['msg'] = 'Company has been saved successfully';
            $alert['type'] = 'success';
              
            return Redirect::route('admin.company')->with('alert', $alert);        
	    }
	}
	
	public function delete($id) {
	    CompanyModel::find($id)->delete();
	    
	    $alert['msg'] = 'Company has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.company')->with('alert', $alert);
	}

    //ajax functions

    public function asyncUpdateCompany() {
        $companyId = Input::get('company_id');
        $companyName = Input::get('company_name');

        $company = CompanyModel::find($companyId);

        $company->name = $companyName;

        $company->save();

        return Response::json(['result' => 'success', 'msg' => 'Company updated successfully.']);
    }
}
