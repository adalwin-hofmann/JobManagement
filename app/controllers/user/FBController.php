<?php namespace User;
use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Mail;
use Pitchanon\FacebookConnect\Provider\FacebookConnect;



class FBController extends \BaseController {

    public function post() {
        // Use a single object of a class throughout the lifetime of an application.
        $application = array(
            'appId' => $_ENV['FACEBOOK_APP_ID'],
            'secret' => $_ENV['FACEBOOK_APP_SECRET']
        );
        $permissions = 'publish_actions, manage_pages';
        $url_app = HTTP_PATH.'fb/post';


        $fbConnect = new FacebookConnect;

        // getInstance
        $fbConnect->getFacebook($application);

        $getUser = $fbConnect->getUser($permissions, $url_app); // Return facebook User data

//        var_dump($getUser);

        $message = array(
            'link'    => HTTP_PATH,
            'message' => 'test message',
            'picture'   => HTTP_IMAGE_PATH.'logo.jpg',
            'name'    => 'test Title',
            'description' => 'test description',
            'access_token' => $getUser['access_token'] // form FacebookConnect::getUser();
        );

        $fbConnect->postToFacebook($message, 'feed'); // return feed id 1330355140_102030093014XXXXX
    }

}