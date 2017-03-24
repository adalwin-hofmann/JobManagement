<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Apply extends Eloquent {
	
    protected $table = 'apply';
    
    public function job() {
    	return $this->belongsTo('Job', 'job_id');
    }
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
    public function notes() {
    	return $this->hasMany('ApplyNote', 'apply_id');
    }
   
}