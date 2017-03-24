<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use City as CityModel;
use Country as CountryModel;

class CityController extends \BaseController {
    
	public function index() {
        $param['cities'] = CityModel::where('name', '<>', '')->paginate(10);
        $param['pageNo'] = 2;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.city.index')->with($param);
	}
	
	public function create() {
		$param['countries'] = CountryModel::all();
		$param['pageNo'] = 2;
		
	    return View::make('admin.city.create')->with($param);
	}
	
	public function edit($id) {
	    $param['city'] = CityModel::find($id);
	    $param['countries'] = CountryModel::all();
	    $param['pageNo'] = 2;
	    
	    return View::make('admin.city.edit')->with($param);
	}
	
	public function store() {
	    
		$rules = ['name'    => 'required'];
	    $validator = Validator::make(Input::all(), $rules);
	    
	    if ($validator->fails()) {
	        return Redirect::back()
	            ->withErrors($validator)
	            ->withInput();
	    } else {
	        $name = Input::get('name');
	        $country_id = Input::get('country_id');
	        
	        if (Input::has('city_id')) {
	            $id = Input::get('city_id');
	            $city = CityModel::find($id);
	            
	            $alert['msg'] = 'City has been updated successfully';
	            $alert['type'] = 'success';
	        } else {
	            $city = new CityModel;	     

	            $alert['msg'] = 'City has been added successfully';
	            $alert['type'] = 'success';
	        }
	        
       
	        
	        $city->name = $name;
	        $city->country_id = $country_id;
	        $city->save();
	          
	        return Redirect::route('admin.city')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {
	    CityModel::find($id)->delete();
	    
	    $alert['msg'] = 'City has been deleted successfully';
	    $alert['type'] = 'success';
	    
	    return Redirect::route('admin.city')->with('alert', $alert);
	}

    //ajax functions

    public function asyncUpdateCity() {
        $cityId = Input::get('city_id');
        $cityName = Input::get('city_name');

        $city = CityModel::find($cityId);

        $city->name = $cityName;

        $city->save();

        return Response::json(['result' => 'success', 'msg' => 'City updated successfully.']);
    }
}
