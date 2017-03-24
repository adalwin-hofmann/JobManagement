<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Message extends Eloquent {
	
    protected $table = 'message';
    
    public function job() {
        return $this->belongsTo('Job', 'job_id');
    }
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
   
}