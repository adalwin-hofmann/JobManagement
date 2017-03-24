<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyUserNote extends Eloquent {
    
	
    protected $table = 'company_user_note';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
}