<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserCollected extends Eloquent {
    
    protected $table = 'user_collected';
    
    public function city() {
    	return $this->belongsTo('City', 'city_id');
    }
    
}