<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyUserScore extends Eloquent {
    
	
    protected $table = 'company_user_score';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
}