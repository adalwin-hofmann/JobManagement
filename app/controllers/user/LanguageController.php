<?php namespace User;
use Illuminate\Routing\Controllers\Controller;
use Redirect, Session;


class LanguageController extends \BaseController {
    public function chooser($lang) {
        Session::set('locale', $lang);
        return Redirect::back();
    }
}