<?php
return [
    "base_url" => $_ENV['LINKEDIN_BASE_URL'],
    "providers" => [ "LinkedIn" => [ "enabled" => TRUE
                   , "keys" => ['key' => $_ENV['LINKEDIN_APP_ID'], 'secret' => $_ENV['LINKEDIN_APP_SECRET']]
                   , "scope" => "r_fullprofile, r_emailaddress" ]
                   ]
    ];
