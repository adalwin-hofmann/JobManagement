<?php
define('SITE_NAME', 'SocialHeadHunter');

define('PAGINATION_SIZE', 10);
define('BUDGET_MIN', 0);
define('BUDGET_MAX', 10000);
define('LOGO', 'default.png');

if (App::environment('local')) {
    define('HTTP_PATH', "http://socialheader.loc/");
    define('REPLY_NAME',        'Local Socialheadhunter');
    define('REPLY_EMAIL',        'noreply@socialheader.loc');
}elseif (App::environment('production')) {
    define('HTTP_PATH', "http://socialheadhunter.org/");
    define('REPLY_NAME',        'Socialheadhunter');
    define('REPLY_EMAIL',        'noreply@socialheadhunter.org');    
}elseif (App::environment('stage')) {
    define('HTTP_PATH', "http://stage.socialheadhunter.org/");
    define('REPLY_NAME',        'Stage Socialheadhunter');
    define('REPLY_EMAIL',        'noreply@stage.socialheadhunter.org');    
}elseif (App::environment('latvia')) {
    define('HTTP_PATH', "http://latvia.socialheadhunter.org/");
    define('REPLY_NAME',        'Latvia Socialheadhunter');
    define('REPLY_EMAIL',        'noreply@latvia.socialheadhunter.org');    
}

define('DEFAULT_COVER_PHOTO', "cover_default.jpg");
define('DEFAULT_COMPANY_PHOTO', "default_company_logo.gif");
define('DEFAULT_COMPANY_OVERLAY_COLOR', "rgba(0, 82, 208, 0.9)");

define('DEFAULT_SLOT_BACKGROUND', "#009CFF");
define('DEFAULT_START_AT', "09:00:00");
define('DEFAULT_END_AT', "18:00:00");

define('HTTP_LOGO_PATH', HTTP_PATH.'assets/logos/');
define('ABS_LOGO_PATH', $_SERVER['DOCUMENT_ROOT'].'/assets/logos/');

define('HTTP_CATEGORY_PATH', HTTP_PATH.'assets/category/');
define('ABS_CATEGORY_PATH', $_SERVER['DOCUMENT_ROOT'].'/assets/category/');

define('HTTP_PHOTO_PATH', HTTP_PATH.'assets/photos/');
define('ABS_PHOTO_PATH', $_SERVER['DOCUMENT_ROOT'].'/assets/photos/');

define('HTTP_COMPANY_PHOTO_PATH', HTTP_PATH.'assets/photos/company/');
define('ABS_COMPANY_PHOTO_PATH', $_SERVER['DOCUMENT_ROOT'].'/assets/photos/company/');

define('HTTP_UPLOAD_PATH', HTTP_PATH.'assets/uploads/');
define('ABS_UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'].'/assets/uploads/');

define('HTTP_VIDEO_PATH', HTTP_PATH.'assets/videos/');
define('ABS_VIDEO_PATH', $_SERVER['DOCUMENT_ROOT'].'/assets/videos/');

define('HTTP_IMAGE_PATH', HTTP_PATH.'assets/img/');
define('ABS_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'].'/assets/img/');
