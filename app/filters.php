<?php
use User as UserModel;
use Company as CompanyModel;

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
    if (App::environment('local')) {
        Lang::setLocale(Session::has('locale') ? Session::get('locale') : 'en');
    }elseif (App::environment('production')) {
        Lang::setLocale(Session::has('locale') ? Session::get('locale') : 'en');
    }elseif (App::environment('stage')) {
        Lang::setLocale(Session::has('locale') ? Session::get('locale') : 'en');
    }elseif (App::environment('latvia')) {
        Lang::setLocale(Session::has('locale') ? Session::get('locale') : 'lv');
    }
    
    if (Cookie::has('ut') && !Session::has('user_id')) {
        $users = UserModel::where('salt', Cookie::get('ut'))->get();
        if (count($users) > 0) {
            Session::set('user_id', $users[0]->id);
            $users[0]->touch();
            return;
        }
    }
    
    if (Cookie::has('ct') && !Session::has('company_id')) {
        $companies = CompanyModel::where('salt', Cookie::get('ct'))->get();
        if (count($companies) > 0) {
            Session::set('company_id', $companies[0]->id);
            Session::set('company_is_admin', $companies[0]->is_admin);
            return;
        }
    }

});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		return Redirect::guest('login');
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

Route::filter('admin-auth', function() {
    if (!Session::has('admin_id')) {
        return Redirect::route('admin.auth.login');
    }
});