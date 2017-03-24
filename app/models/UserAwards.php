<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserAwards extends Eloquent {
    
    protected $table = 'user_awards';
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
}