<?php
return [
    "base_url" => $_ENV['FACEBOOK_BASE_URL'],
    "providers" => [ "Facebook" => [ "enabled" => TRUE
                   , "keys" => ['id' => $_ENV['FACEBOOK_APP_ID'], 'secret' => $_ENV['FACEBOOK_APP_SECRET']]
                   , "scope" => "public_profile, email" ]
                   ]
    ];
