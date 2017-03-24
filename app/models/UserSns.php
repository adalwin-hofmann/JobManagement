<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserSns extends Eloquent {
    
    protected $table = 'user_sns';
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
}