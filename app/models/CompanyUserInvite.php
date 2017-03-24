<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyUserInvite extends Eloquent {
	
    protected $table = 'company_user_invite';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    public function job() {
        return $this->belongsTo('Job', 'job_id');
    }
}