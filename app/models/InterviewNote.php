<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class InterviewNote extends Eloquent {
    
	
    protected $table = 'interview_note';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function cvr() {
    	return $this->belongsTo('CompanyVIResponse', 'cvr_id');
    }
    
}