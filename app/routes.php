<?php

use Illuminate\Support\Facades\Redirect;
Route::pattern('id', '[0-9]+');
Route::pattern('id2', '[0-9]+');
Route::pattern('slug', '[a-z0-9-]+');
Route::pattern('job_slug', '[a-z0-9-]+');
Route::pattern('company_slug', '[a-z0-9-]+');
Route::pattern('code', '[a-zA-Z0-9-]+');
Route::pattern('company_id', '[0-9]+');
Route::pattern('user_id', '[0-9]+');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return Redirect::route('user.job.home');
});


Route::get('home/{id?}',   	                        ['as' => 'user.job.home',                   'uses' => 'User\JobController@home']);
Route::any('search',                                ['as' => 'user.job.search',  	            'uses' => 'User\JobController@search']);
Route::get('signup',                                ['as' => 'user.auth.signup',                'uses' => 'User\AuthController@signup']);
Route::get('signup/{slug}',                         ['as' => 'user.auth.candidateSignUp',       'uses' => 'User\AuthController@candidateSignUp']);
Route::post('doSignup',                             ['as' => 'user.auth.doSignup',              'uses' => 'User\AuthController@doSignup']);
Route::post('doCandidateSignUp',                    ['as' => 'user.auth.doCandidateSignUp',     'uses' => 'User\AuthController@doCandidateSignUp']);
Route::get('user/{slug}/verify',                    ['as' => 'user.auth.verify',                'uses' => 'User\AuthController@verify']);
Route::post('user/doVerify',                        ['as' => 'user.auth.doVerify',              'uses' => 'User\AuthController@doVerify']);

Route::get('auth/{slug}/reset/password',            ['as' => 'user.auth.resetPassword',         'uses' => 'User\AuthController@resetPassword']);


Route::get('login',                                 ['as' => 'user.auth.login',                 'uses' => 'User\AuthController@login']);
Route::post('doLogin',                              ['as' => 'user.auth.doLogin',               'uses' => 'User\AuthController@doLogin']);
Route::get('doLogout',                              ['as' => 'user.auth.doLogout',              'uses' => 'User\AuthController@doLogout']);
Route::post('doResetPassword',                      ['as' => 'user.auth.doResetPassword',       'uses' => 'User\AuthController@doResetPassword']);

Route::get('fbauth/{auth?}',                        ['as' => 'user.auth.fbauth',                'uses' => 'User\AuthController@doFacebookLogin']);
Route::get('gauth/{auth?}',                         ['as' => 'user.auth.gauth',                 'uses' => 'User\AuthController@doGoogleLogin']);
Route::get('lauth/{auth?}',                         ['as' => 'user.auth.lauth',                 'uses' => 'User\AuthController@doLinkedinLogin']);

Route::get('dashboard/{code?}', 	                ['as' => 'user.dashboard', 				    'uses' => 'User\UserController@dashboard']);
Route::get('profile', 		                        ['as' => 'user.dashboard.profile', 		    'uses' => 'User\UserController@profile']);
Route::post('saveProfile', 	                        ['as' => 'user.dashboard.saveProfile', 	    'uses' => 'User\UserController@saveProfile']);
Route::get('cart/{id?}', 	                        ['as' => 'user.dashboard.cart', 		    'uses' => 'User\UserController@cart']);
Route::get('appliedJobs', 	                        ['as' => 'user.dashboard.appliedJobs', 	    'uses' => 'User\UserController@appliedJobs']);
Route::get('recommendations',                       ['as' => 'user.dashboard.recommendations',  'uses' => 'User\UserController@recommendations']);
Route::get('contacts',                              ['as' => 'user.dashboard.contacts',         'uses' => 'User\UserController@contacts']);
Route::get('user/{slug}', 	                        ['as' => 'user.view', 					    'uses' => 'User\UserController@view']);


Route::get('user/company/{slug}', 	                ['as' => 'user.company.view',   	        'uses' => 'User\CompanyController@view']);
Route::get('job/{slug}/{company_id?}', 		        ['as' => 'user.dashboard.viewJob', 	        'uses' => 'User\JobController@viewJob']);
Route::get('job/{slug}/{user_id}/{code}',           ['as' => 'user.dashboard.viewJobForApply', 	'uses' => 'User\JobController@viewJobForApply']);
Route::get('job/{slug}/hint/{id}/verify',           ['as' => 'user.job.verifyHint',             'uses' => 'User\JobController@verifyHint']);
Route::post('job/hint/doVerify',                    ['as' => 'user.job.doVerifyHint',           'uses' => 'User\JobController@doVerifyHint']);

Route::get('user/message/list', 	                ['as' => 'user.message.list',               'uses' => 'User\MessageController@index']);
Route::get('user/message/detail/{slug}/{id2}/{id?}',['as' => 'user.message.detail',             'uses' => 'User\MessageController@detail']);
Route::get('language-chooser/{slug}',               ['as' => 'language-chooser',                'uses' => 'User\LanguageController@chooser']);
Route::post('job/doApply',                          ['as' => 'user.job.doApply',                'uses' => 'User\JobController@doApplyJob']);


Route::get('aboutUs',                               ['as' => 'user.aboutUs',                    'uses' => 'User\UserController@aboutUs']);
Route::get('consumerBasic',                         ['as' => 'user.consumerBasic',              'uses' => 'User\UserController@consumerBasic']);
Route::get('consumers',                             ['as' => 'user.consumers',                  'uses' => 'User\UserController@consumers']);
Route::get('feature/business/small',                ['as' => 'user.featureBusinessSmall',       'uses' => 'User\UserController@featureBusinessSmall']);
Route::get('feature/business',                      ['as' => 'user.featureBusiness',            'uses' => 'User\UserController@featureBusiness']);

Route::get('invite/{slug}/{id}',                    ['as' => 'user.invite.signup',              'uses' => 'User\AuthController@inviteSignUp']);
Route::get('invite/{slug}/{id}/doSignUp',           ['as' => 'user.invite.doSignUp',            'uses' => 'User\AuthController@doInviteSignUp']);

Route::any('search/company',                        ['as' => 'user.company.search',             'uses' => 'User\CompanyController@search']);
Route::post('user/company/doApply',                 ['as' => 'user.company.doApply',            'uses' => 'User\CompanyController@doApply']);

Route::group(['prefix' => 'async'], function () {
	
	Route::post('job/apply', 			            ['as' => 'user.job.async.apply', 				'uses' => 'User\JobController@asyncApply']);
	Route::post('job/checkApply',		            ['as' => 'user.job.async.checkApply', 			'uses' => 'User\JobController@asyncCheckApply']);
	Route::post('job/addToCart', 		            ['as' => 'user.job.async.addToCart', 			'uses' => 'User\JobController@asyncAddToCart']);
	Route::post('job/add/hint', 		            ['as' => 'user.job.async.addHint', 				'uses' => 'User\JobController@asyncAddHint']);
	Route::post('job/send/message', 	            ['as' => 'user.job.async.sendMessage', 			'uses' => 'User\JobController@asyncSendMessage']);
	Route::post('job/removeFromCart',	            ['as' => 'user.job.async.removeFromCart',		'uses' => 'User\JobController@asyncRemoveFromCart']);


    Route::post('user/reset/password',              ['as' => 'user.auth.async.resetPassword',       'uses' => 'User\AuthController@asyncResetPassword']);
    Route::post('user/updateScore',                 ['as' => 'user.async.updateScore',              'uses' => 'User\UserController@asyncUpdateScore']);
	
	Route::post('application/create/template', 	    ['as' => 'user.dashboard.async.createTemplate', 		'uses' => 'User\UserController@asyncCreateTemplate']);
    Route::post('application/edit/template', 	    ['as' => 'user.dashboard.async.editTemplate', 		    'uses' => 'User\UserController@asyncEditTemplate']);
    Route::post('application/delete/template', 	    ['as' => 'user.dashboard.async.deleteTemplate', 		'uses' => 'User\UserController@asyncDeleteTemplate']);
    Route::post('recommendation/remove',            ['as' => 'user.dashboard.async.deleteHint',             'uses' => 'User\UserController@asyncDeleteHint']);
    Route::post('contacts/save',                    ['as' => 'user.dashboard.async.saveContacts',           'uses' => 'User\UserController@asyncSaveContacts']);
    Route::post('contacts/delete',                  ['as' => 'user.dashboard.async.deleteContacts',         'uses' => 'User\UserController@asyncDeleteContacts']);

    Route::post('company/follow',                   ['as' => 'user.async.followCompany',                    'uses' => 'User\UserController@asyncFollowCompany']);
    Route::post('company/unfollow',                 ['as' => 'user.async.unfollowCompany',                  'uses' => 'User\UserController@asyncUnFollowCompany']);
	Route::post('company/add/review', 	            ['as' => 'user.company.async.addReview', 		        'uses' => 'User\CompanyController@asyncAddReview']);

    Route::post('widget/save/user',                 ['as' => 'widget.async.user.save',                      'uses' => 'Widget\MainController@asyncUserSave']);
	Route::post('widget/doLogin',                   ['as' => 'widget.async.doLogin',                        'uses' => 'Widget\MainController@asyncUserLogin']);
    Route::post('widget/doSignUp',                  ['as' => 'widget.async.doSignUp',                       'uses' => 'Widget\MainController@asyncUserSignUp']);
});


Route::group(['prefix' => 'widget'], function() {

    Route::get('{slug}',                            ['as' => 'widget.home',               'uses' => 'Widget\MainController@home']);
    Route::get('{slug}/login',                      ['as' => 'widget.login',              'uses' => 'Widget\MainController@login']);
    Route::get('{slug}/signup',                     ['as' => 'widget.signup',             'uses' => 'Widget\MainController@signup']);
    Route::get('{slug}/job/view/{job_slug}',        ['as' => 'widget.jobView',            'uses' => 'Widget\MainController@jobView']);
    Route::get('{slug}/job/apply/{job_slug?}',      ['as' => 'widget.job.apply',          'uses' => 'Widget\MainController@apply']);
    Route::post('{slug}/job/doApply/{job_slug?}',   ['as' => 'widget.job.doApply',        'uses' => 'Widget\MainController@doApply']);

    Route::post('{slug}/doLogin',                   ['as' => 'widget.doLogin',            'uses' => 'Widget\MainController@doLogin']);
    Route::post('{slug}/doSignup',                  ['as' => 'widget.doSignup',           'uses' => 'Widget\MainController@doSignup']);

    Route::post('async/job/doApply',                ['as' => 'widget.async.doApply',      'uses' => 'Widget\MainController@asyncDoApply']);
    Route::post('async/user/check',                 ['as' => 'widget.async.checkUser',    'uses' => 'Widget\MainController@asyncCheckUser']);


});

Route::group(['prefix' => 'crawl'], function() {

    Route::get('fromMolFi',                 ['as' => 'crawl.getFromMolFi',          'uses'=>'Crawl\CrawlController@getFromMolFi']);
    Route::get('fromMonsterFi',             ['as' => 'crawl.getFromMonsterFi',      'uses'=>'Crawl\CrawlController@getFromMonsterFi']);
    Route::get('fromCVOnline',              ['as' => 'crawl.getFromCVOnline',       'uses'=>'Crawl\CrawlController@getFromCVOnline']);
    Route::get('fromCVLv',                  ['as' => 'crawl.getFromCVLv',           'uses'=>'Crawl\CrawlController@getFromCVLv']);
    Route::get('fromCVLt',                  ['as' => 'crawl.getFromCVLt',           'uses'=>'Crawl\CrawlController@getFromCVLt']);
});


Route::group(['prefix' => 'reminder'], function() {

    Route::get('user/register',                         ['as' => 'reminder.user.register',      'uses'=>'Reminder\ReminderController@userRegister']);

});


Route::group(['prefix' => 'fb'], function() {

    Route::get('post',                                  ['as' => 'fb.post',          'uses'=>'User\FBController@post']);

});


Route::group(['prefix' => 'interview'], function() {

    Route::get('video/{slug}/{company_slug}/step1',             ['as' => 'interview.video.step1',           'uses'=>'Interview\InterviewController@videoInterviewStepOne']);
    Route::get('video/{slug}/{company_slug}/step2',             ['as' => 'interview.video.step2',           'uses'=>'Interview\InterviewController@videoInterviewStepTwo']);
    Route::get('video/{slug}/{company_slug}/step3',             ['as' => 'interview.video.step3',           'uses'=>'Interview\InterviewController@videoInterviewStepThree']);
    Route::get('video/{slug}/{company_slug}/step4',             ['as' => 'interview.video.step4',           'uses'=>'Interview\InterviewController@videoInterviewStepFour']);
    Route::post('video/save',                                   ['as' => 'interview.video.save',            'uses'=>'Interview\InterviewController@videoSave']);
    
    Route::get('face/book/{id}',                                ['as' => 'interview.face.book',             'uses' => 'Interview\InterviewController@faceBook']);
    Route::post('face/doBook',                                  ['as' => 'interview.face.doBook',           'uses' => 'Interview\InterviewController@faceDoBook']);
    
    // Route::get('face/booking/{company_slug}',                   ['as' => 'interview.face.booking',          'uses' => 'Interview\InterviewController@faceBooking']);
    
    Route::group(['prefix' => 'async'], function () {
        Route::post('video/save/responses',                 ['as' => 'interview.video.async.saveResponses', 'uses' => 'Interview\InterviewController@asyncSaveVideoResponses']);
        Route::post('face/create/Booking',                  ['as' => 'interview.face.async.createBooking',  'uses' => 'Interview\InterviewController@asyncCreateFaceBooking']);
    });
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/',         ['as' => 'admin.auth',         'uses' => 'Admin\AdminController@index']);
    Route::get('login',     ['as' => 'admin.auth.login',   'uses' => 'Admin\AdminController@login']);
    Route::post('doLogin',  ['as' => 'admin.auth.doLogin', 'uses' => 'Admin\AdminController@doLogin']);
});

Route::group(['prefix' => 'admin', 'before' => 'admin-auth'], function () {
    Route::get('doLogout',   ['as' => 'admin.auth.doLogout', 'uses' => 'Admin\AdminController@doLogout']);
    
    Route::get('dashboard',  ['as' => 'admin.dashboard',     'uses' => 'Admin\DashboardController@index']);
	
	Route::group(['prefix' => 'country'], function () {		
		Route::get('/',           ['as' => 'admin.country',         'uses' => 'Admin\CountryController@index']);
		Route::get('create',      ['as' => 'admin.country.create',  'uses' => 'Admin\CountryController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.country.edit',    'uses' => 'Admin\CountryController@edit']);
		Route::post('store',      ['as' => 'admin.country.store',   'uses' => 'Admin\CountryController@store']);
		Route::get('delete/{id}', ['as' => 'admin.country.delete',  'uses' => 'Admin\CountryController@delete']);
	});

	Route::group(['prefix' => 'label'], function () {
		Route::get('/',           ['as' => 'admin.label',           'uses' => 'Admin\LabelController@index']);
		Route::get('create',      ['as' => 'admin.label.create',    'uses' => 'Admin\LabelController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.label.edit',      'uses' => 'Admin\LabelController@edit']);
		Route::post('store',      ['as' => 'admin.label.store',     'uses' => 'Admin\LabelController@store']);
		Route::get('delete/{id}', ['as' => 'admin.label.delete',    'uses' => 'Admin\LabelController@delete']);
	});

    Route::group(['prefix' => 'skill'], function () {
        Route::get('/',           ['as' => 'admin.skill',         'uses' => 'Admin\SkillController@index']);
        Route::get('create',      ['as' => 'admin.skill.create',  'uses' => 'Admin\SkillController@create']);
        Route::get('edit/{id}',   ['as' => 'admin.skill.edit',    'uses' => 'Admin\SkillController@edit']);
        Route::post('store',      ['as' => 'admin.skill.store',   'uses' => 'Admin\SkillController@store']);
        Route::get('delete/{id}', ['as' => 'admin.skill.delete',  'uses' => 'Admin\SkillController@delete']);
    });
	
	Route::group(['prefix' => 'city'], function () {
		Route::get('/',           ['as' => 'admin.city',         'uses' => 'Admin\CityController@index']);
		Route::get('create',      ['as' => 'admin.city.create',  'uses' => 'Admin\CityController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.city.edit',    'uses' => 'Admin\CityController@edit']);
		Route::post('store',      ['as' => 'admin.city.store',   'uses' => 'Admin\CityController@store']);
		Route::get('delete/{id}', ['as' => 'admin.city.delete',  'uses' => 'Admin\CityController@delete']);
	});
	
	Route::group(['prefix' => 'service'], function () {
		Route::get('/',           ['as' => 'admin.service',         'uses' => 'Admin\ServiceController@index']);
		Route::get('create',      ['as' => 'admin.service.create',  'uses' => 'Admin\ServiceController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.service.edit',    'uses' => 'Admin\ServiceController@edit']);
		Route::post('store',      ['as' => 'admin.service.store',   'uses' => 'Admin\ServiceController@store']);
		Route::get('delete/{id}', ['as' => 'admin.service.delete',  'uses' => 'Admin\ServiceController@delete']);
	});
	
	Route::group(['prefix' => 'teamsize'], function () {
		Route::get('/',           ['as' => 'admin.teamsize',         'uses' => 'Admin\TeamsizeController@index']);
		Route::get('create',      ['as' => 'admin.teamsize.create',  'uses' => 'Admin\TeamsizeController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.teamsize.edit',    'uses' => 'Admin\TeamsizeController@edit']);
		Route::post('store',      ['as' => 'admin.teamsize.store',   'uses' => 'Admin\TeamsizeController@store']);
		Route::get('delete/{id}', ['as' => 'admin.teamsize.delete',  'uses' => 'Admin\TeamsizeController@delete']);
	});
	
	Route::group(['prefix' => 'language'], function () {
		Route::get('/',           ['as' => 'admin.language',         'uses' => 'Admin\LanguageController@index']);
		Route::get('create',      ['as' => 'admin.language.create',  'uses' => 'Admin\LanguageController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.language.edit',    'uses' => 'Admin\LanguageController@edit']);
		Route::post('store',      ['as' => 'admin.language.store',   'uses' => 'Admin\LanguageController@store']);
		Route::get('delete/{id}', ['as' => 'admin.language.delete',  'uses' => 'Admin\LanguageController@delete']);
	});
	
	Route::group(['prefix' => 'company'], function () {
		Route::get('/',           ['as' => 'admin.company',         'uses' => 'Admin\CompanyController@index']);
		Route::get('create',      ['as' => 'admin.company.create',  'uses' => 'Admin\CompanyController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.company.edit',    'uses' => 'Admin\CompanyController@edit']);
		Route::post('store',      ['as' => 'admin.company.store',   'uses' => 'Admin\CompanyController@store']);
		Route::get('delete/{id}', ['as' => 'admin.company.delete',  'uses' => 'Admin\CompanyController@delete']);
	});
	
	Route::group(['prefix' => 'business'], function () {
		Route::get('/',           ['as' => 'admin.business',         'uses' => 'Admin\BusinessController@index']);
		Route::get('create',      ['as' => 'admin.business.create',  'uses' => 'Admin\BusinessController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.business.edit',    'uses' => 'Admin\BusinessController@edit']);
		Route::post('store',      ['as' => 'admin.business.store',   'uses' => 'Admin\BusinessController@store']);
		Route::get('delete/{id}', ['as' => 'admin.business.delete',  'uses' => 'Admin\BusinessController@delete']);
	});
	
	Route::group(['prefix' => 'user'], function () {
		Route::get('/',           ['as' => 'admin.user',         'uses' => 'Admin\UserController@index']);
		Route::get('create',      ['as' => 'admin.user.create',  'uses' => 'Admin\UserController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.user.edit',    'uses' => 'Admin\UserController@edit']);
		Route::post('store',      ['as' => 'admin.user.store',   'uses' => 'Admin\UserController@store']);
		Route::get('delete/{id}', ['as' => 'admin.user.delete',  'uses' => 'Admin\UserController@delete']);

        Route::get('collected',                 ['as' => 'admin.user.collected',            'uses' => 'Admin\UserController@collected']);
        Route::get('collected/delete/{id}',     ['as' => 'admin.user.collected.delete',     'uses' => 'Admin\UserController@deleteCollectedUser']);
	});
	
	Route::group(['prefix' => 'category'], function () {
		Route::get('/',           ['as' => 'admin.category',         'uses' => 'Admin\CategoryController@index']);
		Route::get('create',      ['as' => 'admin.category.create',  'uses' => 'Admin\CategoryController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.category.edit',    'uses' => 'Admin\CategoryController@edit']);
		Route::post('store',      ['as' => 'admin.category.store',   'uses' => 'Admin\CategoryController@store']);
		Route::get('delete/{id}', ['as' => 'admin.category.delete',  'uses' => 'Admin\CategoryController@delete']);
	});
	
	Route::group(['prefix' => 'level'], function () {
		Route::get('/',           ['as' => 'admin.level',         'uses' => 'Admin\LevelController@index']);
		Route::get('create',      ['as' => 'admin.level.create',  'uses' => 'Admin\LevelController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.level.edit',    'uses' => 'Admin\LevelController@edit']);
		Route::post('store',      ['as' => 'admin.level.store',   'uses' => 'Admin\LevelController@store']);
		Route::get('delete/{id}', ['as' => 'admin.level.delete',  'uses' => 'Admin\LevelController@delete']);
	});
	
	Route::group(['prefix' => 'type'], function () {
		Route::get('/',           ['as' => 'admin.type',         'uses' => 'Admin\TypeController@index']);
		Route::get('create',      ['as' => 'admin.type.create',  'uses' => 'Admin\TypeController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.type.edit',    'uses' => 'Admin\TypeController@edit']);
		Route::post('store',      ['as' => 'admin.type.store',   'uses' => 'Admin\TypeController@store']);
		Route::get('delete/{id}', ['as' => 'admin.type.delete',  'uses' => 'Admin\TypeController@delete']);
	});
	
	Route::group(['prefix' => 'presence'], function () {
		Route::get('/',           ['as' => 'admin.presence',         'uses' => 'Admin\PresenceController@index']);
		Route::get('create',      ['as' => 'admin.presence.create',  'uses' => 'Admin\PresenceController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.presence.edit',    'uses' => 'Admin\PresenceController@edit']);
		Route::post('store',      ['as' => 'admin.presence.store',   'uses' => 'Admin\PresenceController@store']);
		Route::get('delete/{id}', ['as' => 'admin.presence.delete',  'uses' => 'Admin\PresenceController@delete']);
	});
	
	Route::group(['prefix' => 'job'], function () {
		Route::get('/',                 ['as' => 'admin.job',               'uses' => 'Admin\JobController@index']);
		Route::get('news/{id?}',        ['as' => 'admin.job.news',          'uses' => 'Admin\JobController@newJobs']);
		Route::get('create',            ['as' => 'admin.job.create',        'uses' => 'Admin\JobController@create']);
		Route::get('edit/{id}',         ['as' => 'admin.job.edit',          'uses' => 'Admin\JobController@edit']);
		Route::get('news/edit/{id}',    ['as' => 'admin.job.newsEdit',      'uses' => 'Admin\JobController@newJobsEdit']);
        Route::get('news/do/active',    ['as' => 'admin.job.newsActive',    'uses' => 'Admin\JobController@newJobsActive']);
		Route::post('store',            ['as' => 'admin.job.store',         'uses' => 'Admin\JobController@store']);
		Route::get('delete/{id}',       ['as' => 'admin.job.delete',        'uses' => 'Admin\JobController@delete']);
	});
	
	Route::group(['prefix' => 'pattern'], function () {
		Route::get('/',           ['as' => 'admin.pattern',         'uses' => 'Admin\PatternController@index']);
		Route::get('create',      ['as' => 'admin.pattern.create',  'uses' => 'Admin\PatternController@create']);
		Route::get('edit/{id}',   ['as' => 'admin.pattern.edit',    'uses' => 'Admin\PatternController@edit']);
		Route::post('store',      ['as' => 'admin.pattern.store',   'uses' => 'Admin\PatternController@store']);
		Route::get('delete/{id}', ['as' => 'admin.pattern.delete',  'uses' => 'Admin\PatternController@delete']);
	});
	
    Route::group(['prefix' => 'email'], function () {
        Route::get('/',           ['as' => 'admin.email',           'uses' => 'Admin\EmailController@index']);
        Route::get('create',      ['as' => 'admin.email.create',    'uses' => 'Admin\EmailController@create']);
        Route::get('edit/{id}',   ['as' => 'admin.email.edit',      'uses' => 'Admin\EmailController@edit']);
        Route::post('store',      ['as' => 'admin.email.store',     'uses' => 'Admin\EmailController@store']);
        Route::get('delete/{id}', ['as' => 'admin.email.delete',    'uses' => 'Admin\EmailController@delete']);
    });
    
    Route::group(['prefix' => 'setting'], function () {
        Route::get('/',           ['as' => 'admin.setting',              'uses' => 'Admin\SettingController@index']);
        Route::get('create',      ['as' => 'admin.setting.create',       'uses' => 'Admin\SettingController@create']);
        Route::get('edit/{id}',   ['as' => 'admin.setting.edit',         'uses' => 'Admin\SettingController@edit']);
        Route::post('store',      ['as' => 'admin.setting.store',        'uses' => 'Admin\SettingController@store']);
        Route::get('delete/{id}', ['as' => 'admin.setting.delete',       'uses' => 'Admin\SettingController@delete']);
    });
    
    Route::group(['prefix' => 'group'], function () {
        Route::get('/',           ['as' => 'admin.group',                'uses' => 'Admin\GroupController@index']);
        Route::get('create',      ['as' => 'admin.group.create',         'uses' => 'Admin\GroupController@create']);
        Route::get('edit/{id}',   ['as' => 'admin.group.edit',           'uses' => 'Admin\GroupController@edit']);
        Route::post('store',      ['as' => 'admin.group.store',          'uses' => 'Admin\GroupController@store']);
        Route::get('delete/{id}', ['as' => 'admin.group.delete',         'uses' => 'Admin\GroupController@delete']);
        Route::get('marketing/{id}', ['as' => 'admin.group.marketing',   'uses' => 'Admin\GroupController@marketing']);
        Route::post('doMarketing',   ['as' => 'admin.group.doMarketing', 'uses' => 'Admin\GroupController@doMarketing']);
        Route::get('includeCompany/{id}/{id2}',     ['as' => 'admin.group.includeCompany',         'uses' => 'Admin\GroupController@includeCompany']);
        Route::get('excludeCompany/{id}/{id2}',     ['as' => 'admin.group.excludeCompany',         'uses' => 'Admin\GroupController@excludeCompany']);
        
    });

    Route::group(['prefix' => 'other'], function () {
        Route::get('/',           ['as' => 'admin.other',         'uses' => 'Admin\OtherController@index']);

        Route::group(['prefix' => 'async'], function () {
            Route::post('update/levelScore',               ['as' => 'admin.other.async.updateLevelScore',           'uses' => 'Admin\OtherController@asyncUpdateLevelScore']);
        });
    });

    Route::group(['prefix' => 'async'], function () {
        Route::post('update/company',               ['as' => 'admin.async.updateCompany',           'uses' => 'Admin\CompanyController@asyncUpdateCompany']);
        Route::post('update/city',                  ['as' => 'admin.async.updateCity',              'uses' => 'Admin\CityController@asyncUpdateCity']);
        Route::post('update/category',              ['as' => 'admin.async.updateCategory',          'uses' => 'Admin\CategoryController@asyncUpdateCategory']);
        Route::post('update/job/status',            ['as' => 'admin.async.updateJobStatus',         'uses' => 'Admin\JobController@asyncUpdateJobStatus']);
        Route::post('update/job/contact',           ['as' => 'admin.async.updateJobContact',        'uses' => 'Admin\JobController@asyncUpdateJobContact']);
        Route::post('update/job/link',              ['as' => 'admin.async.updateJobLink',           'uses' => 'Admin\JobController@asyncUpdateJobLink']);
    });
});

Route::group(['prefix' => 'company'], function () {

	Route::any('/',                             ['as' => 'company.dashboard',                   'uses' => 'Company\CompanyController@index']);
	Route::get('profile', 		                ['as' => 'company.profile', 		            'uses' => 'Company\CompanyController@profile']);
	Route::post('saveProfile',                  ['as' => 'company.saveProfile', 	            'uses' => 'Company\CompanyController@saveProfile']);


    Route::get('auth/{slug}/reset/password',    ['as' => 'company.auth.resetPassword',          'uses' => 'Company\AuthController@resetPassword']);
	
	Route::get('signup',		                ['as' => 'company.auth.signup',                 'uses' => 'Company\AuthController@signup']);
	Route::post('doSignup', 	                ['as' => 'company.auth.doSignup',               'uses' => 'Company\AuthController@doSignup']);
	Route::get('login',                         ['as' => 'company.auth.login',                  'uses' => 'Company\AuthController@login']);
	Route::post('doLogin',                      ['as' => 'company.auth.doLogin',                'uses' => 'Company\AuthController@doLogin']);
	Route::get('doLogout',                      ['as' => 'company.auth.doLogout',               'uses' => 'Company\AuthController@doLogout']);

	Route::post('referSignup', 	                ['as' => 'company.auth.referSignup',            'uses' => 'Company\AuthController@referSignup']);

    Route::post('doResetPassword',              ['as' => 'company.auth.doResetPassword',        'uses' => 'Company\AuthController@doResetPassword']);
	Route::get('{slug}/verify',                 ['as' => 'company.auth.verify',                 'uses' => 'Company\AuthController@verify']);
    Route::post('doVerify',                     ['as' => 'company.auth.doVerify',               'uses' => 'Company\AuthController@doVerify']);

	Route::post('member/doSignup', 		        ['as' => 'company.auth.member.doSignup', 	    'uses' => 'Company\AuthController@doMemberSignUp']);
	
	Route::get('addjob', 			            ['as' => 'company.job.add', 		            'uses' => 'Company\JobController@add']);
	Route::post('doAddJob',			            ['as' => 'company.job.doAddJob', 	            'uses' => 'Company\JobController@doAdd']);
	Route::get('myjobs/{id?}',  	            ['as' => 'company.job.myjobs', 		            'uses' => 'Company\JobController@myJobs']);
	Route::get('job/{slug}/{id?}', 	            ['as' => 'company.job.view', 		            'uses' => 'Company\JobController@view']);
	
	Route::get('message/list', 	                        ['as' => 'company.message.list',        'uses' => 'Company\MessageController@index']);
	Route::get('message/detail/{slug}/{id}/{id2?}',     ['as' => 'company.message.detail',      'uses' => 'Company\MessageController@detail']);
	
	Route::get('setting', 	                            ['as' => 'company.setting',             'uses' => 'Company\SettingController@index']);
	Route::post('setting/store', 	                    ['as' => 'company.setting.store',       'uses' => 'Company\SettingController@store']);
	
	Route::get('delete/member/{id}', 	        ['as' => 'company.user.delete', 	            'uses' => 'Company\UserController@deleteMember']);
	Route::post('edit/member', 			        ['as' => 'company.user.edit', 		            'uses' => 'Company\UserController@editMember']);

    Route::any('find/people',                   ['as' => 'company.user.find',                   'uses' => 'Company\UserController@findPeople']);
    Route::any('applied/people',                ['as' => 'company.user.applied',                'uses' => 'Company\UserController@appliedPeople']);
    Route::any('shared/people',                 ['as' => 'company.user.shared',                 'uses' => 'Company\UserController@sharedPeople']);

    Route::get('candidates',                    ['as' => 'company.user.candidates',             'uses' => 'Company\UserController@candidates']);

    Route::get('interview/face',                ['as' => 'company.interview.face',              'uses' => 'Company\InterviewController@face']);
    Route::get('interview/video',               ['as' => 'company.interview.video',             'uses' => 'Company\InterviewController@video']);
    Route::get('interview/shared',              ['as' => 'company.interview.shared',            'uses' => 'Company\InterviewController@shared']);

    Route::get('share',                         ['as' => 'company.share',                       'uses' => 'Company\ShareController@index']);
    Route::get('link/share/{slug}',             ['as' => 'company.share.link',                  'uses' => 'Company\ShareController@linkToIndex']);
    Route::get('{slug}/share/{id}',             ['as' => 'company.share.viewOnApp',             'uses' => 'Company\ShareController@viewOnApp']);

	Route::group(['prefix' => 'async'], function () {

		Route::post('job/save/notes', 		        ['as' => 'company.job.async.saveNotes', 		        'uses' => 'Company\JobController@asyncSaveNotes']);
		Route::post('job/hint/save/notes', 	        ['as' => 'company.job.async.saveHintNotes', 	        'uses' => 'Company\JobController@asyncSaveHintNotes']);
		Route::post('job/user/send/message',        ['as' => 'company.job.async.sendMessage', 		        'uses' => 'Company\JobController@asyncSendMessage']);
		Route::post('job/hint/send/message',        ['as' => 'company.job.async.sendHintMessage',	        'uses' => 'Company\JobController@asyncSendMessageHint']);
		Route::post('job/status/update',            ['as' => 'company.job.async.updateStatus',              'uses' => 'Company\JobController@asyncUpdateStatus']);
		Route::post('job/bonus/update',             ['as' => 'company.job.async.updateBonus',               'uses' => 'Company\JobController@asyncUpdateBonus']);		
		Route::post('job/reject/apply', 	        ['as' => 'company.job.async.rejectApply', 		        'uses' => 'Company\JobController@asyncRejectApply']);
		Route::post('job/reject/hint', 		        ['as' => 'company.job.async.rejectHint', 		        'uses' => 'Company\JobController@asyncRejectHint']);
        Route::post('job/apply/save/rate',          ['as' => 'company.job.async.saveApplyRate',             'uses' => 'Company\JobController@asyncSaveApplyRate']);
        Route::post('job/hint/save/rate',           ['as' => 'company.job.async.saveHintRate',              'uses' => 'Company\JobController@asyncSaveHintRate']);
        Route::post('job/send/interview',           ['as' => 'company.job.async.sendInterview',             'uses' => 'Company\JobController@asyncSendInterview']);
        Route::post('job/interview/save/note',      ['as' => 'company.job.async.saveInterviewNote',         'uses' => 'Company\JobController@asyncSaveInterviewNote']);
        Route::post('job/save/share/note',          ['as' => 'company.job.async.saveShareNote',             'uses' => 'Company\JobController@asyncSaveShareNote']);

		Route::post('user/update/status', 	        ['as' => 'company.user.async.updateStatus', 	        'uses' => 'Company\UserController@asyncUpdateStatus']);
        Route::post('user/hint/update/status',      ['as' => 'company.user.async.updateHintStatus',         'uses' => 'Company\UserController@asyncUpdateHintStatus']);
		Route::post('user/add/member', 	            ['as' => 'company.user.async.addMember', 		        'uses' => 'Company\UserController@asyncAddMember']);
		Route::post('user/update/member', 	        ['as' => 'company.user.async.updateMember', 	        'uses' => 'Company\UserController@asyncUpdateMember']);
		Route::post('user/notes/save', 		        ['as' => 'company.user.async.saveNotes', 		        'uses' => 'Company\UserController@asyncSaveNotes']);
		Route::post('user/request/feedback',        ['as' => 'company.user.async.requestFeedback', 	        'uses' => 'Company\UserController@asyncRequestFeedback']);
        Route::post('user/save/rate',               ['as' => 'company.user.async.saveRate',                 'uses' => 'Company\UserController@asyncSaveRate']);
        Route::post('user/send/message', 	        ['as' => 'company.user.async.sendMessage', 	            'uses' => 'Company\UserController@asyncSendMessage']);
        Route::post('user/send/invite', 	        ['as' => 'company.user.async.sendInvite', 	            'uses' => 'Company\UserController@asyncSendInvite']);
        Route::post('user/view',                    ['as' => 'company.user.async.view',                     'uses' => 'Company\UserController@asyncUserView']);
        Route::post('user/add/candidate',           ['as' => 'company.user.async.addCandidate',             'uses' => 'Company\UserController@asyncAddCandidate']);
        Route::post('user/addTo/candidate',         ['as' => 'company.user.async.addToCandidate',           'uses' => 'Company\UserController@asyncAddToCandidate']);
        Route::post('user/send/interview',          ['as' => 'company.user.async.sendInterview',            'uses' => 'Company\UserController@asyncSendInterview']);
        Route::post('user/check/availableJobs',     ['as' => 'company.user.async.checkAvailableJobs',       'uses' => 'Company\UserController@asyncCheckAvailableJobs']);
        Route::post('user/moveTo/job',              ['as' => 'company.user.async.moveToJob',                'uses' => 'Company\UserController@asyncMoveToJob']);

        Route::post('shared/user/notes/save', 		['as' => 'company.user.async.saveShareNotes', 		    'uses' => 'Company\UserController@asyncSaveShareNotes']);

        Route::post('company/view/interview',       ['as' => 'company.profile.async.viewInterview',         'uses' => 'Company\CompanyController@asyncViewInterview']);
        Route::post('company/save/question',        ['as' => 'company.profile.async.saveQuestion',          'uses' => 'Company\CompanyController@asyncSaveQuestion']);
        Route::post('company/delete/question',      ['as' => 'company.profile.async.deleteQuestion',        'uses' => 'Company\CompanyController@asyncDeleteQuestion']);
        Route::post('company/save/questionnaire',   ['as' => 'company.profile.async.saveQuestionnaire',     'uses' => 'Company\CompanyController@asyncSaveQuestionnaire']);
        Route::post('company/delete/questionnaire', ['as' => 'company.profile.async.deleteQuestionnaire',   'uses' => 'Company\CompanyController@asyncDeleteQuestionnaire']);
        Route::post('company/save/viTemplate',      ['as' => 'company.profile.async.saveVITemplate',        'uses' => 'Company\CompanyController@asyncSaveVITemplate']);
        Route::post('company/delete/viTemplate',    ['as' => 'company.profile.async.deleteVITemplate',      'uses' => 'Company\CompanyController@asyncDeleteVITemplate']);
        Route::post('company/save/applyNote',       ['as' => 'company.profile.async.saveApplyNote',         'uses' => 'Company\CompanyController@asyncSaveApplyNote']);
        Route::post('company/user/send/message',    ['as' => 'company.profile.async.sendMessage', 		    'uses' => 'Company\CompanyController@asyncSendMessage']);
        Route::post('company/reset/password',       ['as' => 'company.auth.async.resetPassword',            'uses' => 'Company\AuthController@asyncResetPassword']);

        Route::post('agency/view',                  ['as' => 'company.agency.async.view',                   'uses' => 'Company\AgencyController@asyncView']);
        
        Route::post('interview/face/doSchedule',       ['as' => 'company.interview.async.face.doSchedule',        'uses' => 'Company\InterviewController@asyncFaceDoSchedule']);
        Route::post('interview/face/doInvite',         ['as' => 'company.interview.async.face.doInvite',          'uses' => 'Company\InterviewController@asyncFaceDoInvite']);
        Route::post('interview/face/loadBookingInfo',  ['as' => 'company.interview.async.face.loadBookingInfo',   'uses' => 'Company\InterviewController@asyncFaceLoadBookingInfo']);        
    });
});


Route::group(['prefix' => 'agency'], function () {

    Route::any('/',                                     ['as' => 'agency.dashboard',                    'uses' => 'Agency\AgencyController@index']);
    Route::any('find/people',                           ['as' => 'agency.user.find',                    'uses' => 'Agency\UserController@findPeople']);
    Route::any('applied/people',                        ['as' => 'agency.user.applied',                 'uses' => 'Agency\UserController@appliedPeople']);

    Route::get('message/list', 	                        ['as' => 'agency.message.list',                 'uses' => 'Agency\MessageController@index']);
    Route::get('message/detail/{slug}/{id}/{id2?}',     ['as' => 'agency.message.detail',               'uses' => 'Agency\MessageController@detail']);
    Route::get('profile', 		                        ['as' => 'agency.profile', 		                'uses' => 'Agency\AgencyController@profile']);
    Route::get('signup',		                        ['as' => 'agency.auth.signup',                  'uses' => 'Agency\AuthController@signup']);
    Route::get('login',                                 ['as' => 'agency.auth.login',                   'uses' => 'Agency\AuthController@login']);
    Route::get('doLogout',                              ['as' => 'agency.auth.doLogout',                'uses' => 'Agency\AuthController@doLogout']);
    Route::get('{slug}/verify',                         ['as' => 'agency.auth.verify',                  'uses' => 'Agency\AuthController@verify']);
    Route::get('auth/{slug}/reset/password',            ['as' => 'agency.auth.resetPassword',           'uses' => 'Agency\AuthController@resetPassword']);
    Route::get('add/job', 			                    ['as' => 'agency.job.add', 		                'uses' => 'Agency\JobController@add']);
    Route::get('myjobs/{id?}',  	                    ['as' => 'agency.job.myjobs', 		            'uses' => 'Agency\JobController@myJobs']);
    Route::get('job/{slug}/{id?}', 	                    ['as' => 'agency.job.view', 		            'uses' => 'Agency\JobController@view']);
    Route::get('delete/member/{id}', 	                ['as' => 'agency.user.delete', 	                'uses' => 'Agency\AgencyController@deleteMember']);
    Route::get('candidates',                            ['as' => 'agency.user.candidates',              'uses' => 'Agency\UserController@candidates']);
    Route::get('share',                                 ['as' => 'agency.share',                        'uses' => 'Agency\ShareController@index']);
    Route::get('company',                               ['as' => 'agency.company.index',                'uses' => 'Agency\CompanyController@index']);

    Route::post('edit/member', 			                ['as' => 'agency.user.edit', 		            'uses' => 'Agency\AgencyController@editMember']);
    Route::post('saveProfile',                          ['as' => 'agency.saveProfile', 	                'uses' => 'Agency\AgencyController@saveProfile']);
    Route::post('doResetPassword',                      ['as' => 'agency.auth.doResetPassword',         'uses' => 'Agency\AuthController@doResetPassword']);
    Route::post('doVerify',                             ['as' => 'agency.auth.doVerify',                'uses' => 'Agency\AuthController@doVerify']);
    Route::post('referSignup', 	                        ['as' => 'agency.auth.referSignup',             'uses' => 'Agency\AuthController@referSignup']);
    Route::post('doLogin',                              ['as' => 'agency.auth.doLogin',                 'uses' => 'Agency\AuthController@doLogin']);
    Route::post('doSignup', 	                        ['as' => 'agency.auth.doSignup',                'uses' => 'Agency\AuthController@doSignup']);
    Route::post('doAddJob',			                    ['as' => 'agency.job.doAddJob', 	            'uses' => 'Agency\JobController@doAdd']);
    
    Route::group(['prefix' => 'async'], function () {

        Route::post('job/save/notes', 		        ['as' => 'agency.job.async.saveNotes', 		            'uses' => 'Agency\JobController@asyncSaveNotes']);
        Route::post('job/hint/save/notes', 	        ['as' => 'agency.job.async.saveHintNotes', 	            'uses' => 'Agency\JobController@asyncSaveHintNotes']);
        Route::post('job/user/send/message',        ['as' => 'agency.job.async.sendMessage', 		        'uses' => 'Agency\JobController@asyncSendMessage']);
        Route::post('job/hint/send/message',        ['as' => 'agency.job.async.sendHintMessage',	        'uses' => 'Agency\JobController@asyncSendMessageHint']);
        Route::post('job/status/update',            ['as' => 'agency.job.async.updateStatus',               'uses' => 'Agency\JobController@asyncUpdateStatus']);
        Route::post('job/bonus/update',             ['as' => 'agency.job.async.updateBonus',                'uses' => 'Agency\JobController@asyncUpdateBonus']);
        Route::post('job/reject/apply', 	        ['as' => 'agency.job.async.rejectApply', 		        'uses' => 'Agency\JobController@asyncRejectApply']);
        Route::post('job/reject/hint', 		        ['as' => 'agency.job.async.rejectHint', 		        'uses' => 'Agency\JobController@asyncRejectHint']);
        Route::post('job/apply/save/rate',          ['as' => 'agency.job.async.saveApplyRate',              'uses' => 'Agency\JobController@asyncSaveApplyRate']);
        Route::post('job/hint/save/rate',           ['as' => 'agency.job.async.saveHintRate',               'uses' => 'Agency\JobController@asyncSaveHintRate']);
        Route::post('job/send/interview',           ['as' => 'agency.job.async.sendInterview',              'uses' => 'Agency\JobController@asyncSendInterview']);
        Route::post('job/interview/save/note',      ['as' => 'agency.job.async.saveInterviewNote',          'uses' => 'Agency\JobController@asyncSaveInterviewNote']);

        Route::post('user/update/status', 	        ['as' => 'agency.user.async.updateStatus', 	            'uses' => 'Agency\UserController@asyncUpdateStatus']);
        Route::post('user/hint/update/status',      ['as' => 'agency.user.async.updateHintStatus',          'uses' => 'Agency\UserController@asyncUpdateHintStatus']);
        Route::post('user/add/member', 	            ['as' => 'agency.user.async.addMember', 		        'uses' => 'Agency\UserController@asyncAddMember']);
        Route::post('user/update/member', 	        ['as' => 'agency.user.async.updateMember', 	            'uses' => 'Agency\UserController@asyncUpdateMember']);
        Route::post('user/notes/save', 		        ['as' => 'agency.user.async.saveNotes', 		        'uses' => 'Agency\UserController@asyncSaveNotes']);
        Route::post('user/request/feedback',        ['as' => 'agency.user.async.requestFeedback', 	        'uses' => 'Agency\UserController@asyncRequestFeedback']);
        Route::post('user/save/rate',               ['as' => 'agency.user.async.saveRate',                  'uses' => 'Agency\UserController@asyncSaveRate']);
        Route::post('user/send/message', 	        ['as' => 'agency.user.async.sendMessage', 	            'uses' => 'Agency\UserController@asyncSendMessage']);
        Route::post('user/send/invite', 	        ['as' => 'agency.user.async.sendInvite', 	            'uses' => 'Agency\UserController@asyncSendInvite']);
        Route::post('user/view',                    ['as' => 'agency.user.async.view',                      'uses' => 'Agency\UserController@asyncUserView']);
        Route::post('user/add/candidate',           ['as' => 'agency.user.async.addCandidate',              'uses' => 'Agency\UserController@asyncAddCandidate']);
        Route::post('user/addTo/candidate',         ['as' => 'agency.user.async.addToCandidate',            'uses' => 'Agency\UserController@asyncAddToCandidate']);
        Route::post('user/send/interview',          ['as' => 'agency.user.async.sendInterview',             'uses' => 'Agency\UserController@asyncSendInterview']);
        Route::post('user/check/availableJobs',     ['as' => 'agency.user.async.checkAvailableJobs',        'uses' => 'Agency\UserController@asyncCheckAvailableJobs']);
        Route::post('user/moveTo/job',              ['as' => 'agency.user.async.moveToJob',                 'uses' => 'Agency\UserController@asyncMoveToJob']);
        Route::post('user/add/label',               ['as' => 'agency.user.async.addLabel',                  'uses' => 'Agency\UserController@asyncAddLabel']);
        Route::post('user/remove/label',            ['as' => 'agency.user.async.removeLabel',               'uses' => 'Agency\UserController@asyncRemoveLabel']);
        Route::post('user/detail/view',             ['as' => 'agency.user.async.detailView',                'uses' => 'Agency\UserController@asyncDetailView']);
        Route::post('user/applied/detail/view',     ['as' => 'agency.user.async.appliedDetailView',         'uses' => 'Agency\UserController@asyncAppliedDetailView']);

        Route::post('share/company/list/byJob',         ['as' => 'agency.share.async.getCompanyListByJob',      'uses' => 'Agency\ShareController@asyncGetCompanyListByJob']);
        Route::post('share/company/list/byUser',        ['as' => 'agency.share.async.getCompanyListByUser',     'uses' => 'Agency\ShareController@asyncGetCompanyListByUser']);
        Route::post('share/company/list/byInterview',   ['as' => 'agency.share.async.getCompanyListByInterview','uses' => 'Agency\ShareController@asyncGetCompanyListByInterview']);
        Route::post('doShare/company',                  ['as' => 'agency.share.async.shareToCompany',           'uses' => 'Agency\ShareController@asyncShareToCompany']);
        Route::post('share/remove',                     ['as' => 'agency.share.async.removeShare',              'uses' => 'Agency\ShareController@asyncRemoveShare']);

        Route::post('agency/view/interview',        ['as' => 'agency.profile.async.viewInterview',          'uses' => 'Agency\AgencyController@asyncViewInterview']);
        Route::post('agency/save/question',         ['as' => 'agency.profile.async.saveQuestion',           'uses' => 'Agency\AgencyController@asyncSaveQuestion']);
        Route::post('agency/delete/question',       ['as' => 'agency.profile.async.deleteQuestion',         'uses' => 'Agency\AgencyController@asyncDeleteQuestion']);
        Route::post('agency/save/questionnaire',    ['as' => 'agency.profile.async.saveQuestionnaire',      'uses' => 'Agency\AgencyController@asyncSaveQuestionnaire']);
        Route::post('agency/delete/questionnaire',  ['as' => 'agency.profile.async.deleteQuestionnaire',    'uses' => 'Agency\AgencyController@asyncDeleteQuestionnaire']);
        Route::post('agency/save/viTemplate',       ['as' => 'agency.profile.async.saveVITemplate',         'uses' => 'Agency\AgencyController@asyncSaveVITemplate']);
        Route::post('agency/delete/viTemplate',     ['as' => 'agency.profile.async.deleteVITemplate',       'uses' => 'Agency\AgencyController@asyncDeleteVITemplate']);
        Route::post('agency/save/applyNote',        ['as' => 'agency.profile.async.saveApplyNote',          'uses' => 'Agency\AgencyController@asyncSaveApplyNote']);
        Route::post('agency/user/send/message',     ['as' => 'agency.profile.async.sendMessage', 		    'uses' => 'Agency\AgencyController@asyncSendMessage']);

        Route::post('agency/reset/password',        ['as' => 'agency.auth.async.resetPassword',             'uses' => 'Agency\AuthController@asyncResetPassword']);

        Route::post('company/set/client',           ['as' => 'agency.company.async.setClient',              'uses' => 'Agency\CompanyController@asyncSetClient']);
        Route::post('company/remove/client',        ['as' => 'agency.company.async.removeClient',           'uses' => 'Agency\CompanyController@asyncRemoveClient']);
        Route::post('company/add',                  ['as' => 'agency.company.async.add',                    'uses' => 'Agency\CompanyController@asyncAdd']);

    });
});

Route::group(['prefix' => 'batch'], function () {
    Route::get('user/readEmail',                       ['as' => 'batch.user.readEmail',                     'uses' => 'Batch\ReadEmailController@checkRead']);
    Route::get('company/recommendedJobReminder/{id?}', ['as' => 'batch.company.recommendedJobReminder',     'uses' => 'Batch\MarketingMessageController@recommendedJobReminder']);
    Route::get('company/loginReminder/{id?}',          ['as' => 'batch.company.loginReminder',              'uses' => 'Batch\MarketingMessageController@companyLoginReminder']);
    Route::get('company/jobAddedNotRegistered/{id?}',  ['as' => 'batch.company.jobAddedNotRegistered',      'uses' => 'Batch\MarketingMessageController@companyJobAddedNotRegistered']);
    Route::get('company/jobAddedRegistered/{id?}',     ['as' => 'batch.company.jobAddedNotRegistered',      'uses' => 'Batch\MarketingMessageController@companyJobAddedRegistered']);
    Route::get('company/setRecommendationBonus',       ['as' => 'batch.company.setRecommendationBonus',     'uses' => 'Batch\MarketingMessageController@setRecommendationBonus']);
    Route::get('company/createVideoInterview',         ['as' => 'batch.company.createVideoInterview',       'uses' => 'Batch\MarketingMessageController@createVideoInterview']);    
    Route::get('user/loginReminder/{id?}',             ['as' => 'batch.user.loginReminder',                 'uses' => 'Batch\MarketingMessageController@userLoginReminder']);
});


App::missing(function($exception) {
    return Redirect::route('user.job.home');
});