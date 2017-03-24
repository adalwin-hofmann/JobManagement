<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyApply extends Eloquent {
	
    protected $table = 'company_apply';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
    public function notes() {
    	return $this->hasMany('CompanyApplyNote', 'apply_id');
    }
   
}