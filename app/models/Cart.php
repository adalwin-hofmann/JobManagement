<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Cart extends Eloquent {
	
    protected $table = 'cart';
    
    public function job() {
    	return $this->belongsTo('Job', 'job_id');
    }
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
   
}