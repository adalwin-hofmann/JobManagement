<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyCandidates extends Eloquent {
	
    protected $table = 'company_candidates';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}