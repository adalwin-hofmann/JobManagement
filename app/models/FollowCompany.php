<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class FollowCompany extends Eloquent {
    
    protected $table = 'follow_company';
    
    public function Company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
}