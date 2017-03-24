<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class JobLanguage extends Eloquent {
    
    protected $table = 'job_language';
    
    public function job() {
    	return $this->belongsTo('Job', 'job_id');
    }
    
    public function language() {
    	return $this->belongsTo('language', 'language_id');
    }
    
}