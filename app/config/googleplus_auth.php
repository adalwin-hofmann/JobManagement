<?php
return array(
    "base_url" => $_ENV['GOOGLEPLUS_BASE_URL'],
    "providers" => array(
        "Google" => array(
            "enabled" => TRUE,
            "keys" => array("id" => $_ENV['GOOGLEPLUS_APP_ID'], "secret" => $_ENV['GOOGLEPLUS_APP_SECRET']),
            "scope" => "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email", )
    )
);