<?php namespace Batch;

use Illuminate\Routing\Controllers\Controller;
use View, Input, Redirect, Session, Validator, Log;
use Apply as ApplyModel;

class ReadEmailController extends \BaseController {
    
    public function checkRead() {
        $applies = ApplyModel::whereRaw("DATE_ADD(NOW(), INTERVAL -72 HOUR) <= updated_at")
                               ->where('status', 0)
                               ->get();

        foreach ($applies as $apply) {
            
            if ($apply->token == '') continue;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/api/stats.get.json');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);

            $data = [ 'api_user'   => $_ENV['SENDGRID_USER'],
                      'api_key'    => $_ENV['SENDGRID_PASS'],
                      'category'   => $apply->token,
                      'aggregate'  => 1
                    ];
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            curl_close($ch);

            $statistics = json_decode( $result, true );
            
            if( !isset($statistics["error"]) ){
                $count_open = 0;
                $count_click = 0;
                foreach ($statistics as $stat) {
                    $count_open += isset($stat['opens']) ? $stat['opens'] : 0;
                    $count_click += isset($stat['clicks']) ? $stat['clicks'] : 0;
                }
                if ($count_open > 0 || $count_click > 0) {
                    $apply->status = 1;
                    $apply->save();
                }
            }            
        }
    }
}
