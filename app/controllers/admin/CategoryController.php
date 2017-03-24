<?php namespace Admin;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Response;
use Category as CategoryModel;

class CategoryController extends \BaseController {
    
	public function index() {
        $param['categories'] = CategoryModel::paginate(10);
        $param['pageNo'] = 6;
        
	    if ($alert = Session::get('alert')) {
            $param['alert'] = $alert;
        }
        
		return View::make('admin.category.index')->with($param);
	}
	
	public function create() {
		$param['pageNo'] = 6;
		
	    return View::make('admin.category.create')->with($param);
	}
	
	public function edit($id) {
	    $param['category'] = CategoryModel::find($id);
        $param['categories'] = CategoryModel::all();
	    $param['pageNo'] = 6;
	    
	    return View::make('admin.category.edit')->with($param);
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
            $parent_id = Input::get('parent_id');
	        
	        if (Input::has('category_id')) {
	            $id = Input::get('category_id');
	            $category = CategoryModel::find($id);
	        } else {
	            $category = new CategoryModel;	     
	        }
	        $category->name = $name;
            if ($parent_id != '') {
                $category->parent_id = $parent_id;
            }
            
            if (Input::hasFile('photo')) {
                $filename = str_random(8).".".Input::file('photo')->getClientOriginalExtension();
                Input::file('photo')->move(ABS_CATEGORY_PATH, $filename);
                $category->photo = $filename;
            }

	        $category->save();
	        
	        $alert['msg'] = 'Category has been saved successfully';
	        $alert['type'] = 'success';	          
	        return Redirect::route('admin.category')->with('alert', $alert);	        
	    }
	}
	
	public function delete($id) {

	    
	    try {
		    CategoryModel::find($id)->delete();
		    
		    $alert['msg'] = 'Category has been deleted successfully';
		    $alert['type'] = 'success';
	    } catch(\Exception $ex) {
	    	$alert['msg'] = 'This category has been already used';
	    	$alert['type'] = 'danger';
	    }
	    
	    return Redirect::route('admin.category')->with('alert', $alert);
	}


    //ajax functions

    public function asyncUpdateCategory() {
        $categoryId = Input::get('category_id');
        $categoryName = Input::get('category_name');

        $category = CategoryModel::find($categoryId);

        $category->name = $categoryName;

        $category->save();

        return Response::json(['result' => 'success', 'msg' => 'Category updated successfully.']);
    }
}
