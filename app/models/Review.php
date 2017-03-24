<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Review extends Eloquent {
    
	
    protected $table = 'review';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
}