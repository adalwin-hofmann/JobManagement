<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyVIResponse extends Eloquent {
	
    protected $table = 'company_vi_response';
    
    public function cvc() {
    	return $this->belongsTo('CompanyVICreated', 'cvc_id');
    }

    public function question() {
        return $this->belongsTo('Questions', 'question_id');
    }

    public function notes() {
        return $this->hasMany('InterviewNote', 'cvr_id');
    }
}