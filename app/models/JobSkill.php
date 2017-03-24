<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class JobSkill extends Eloquent {
    
    protected $table = 'job_skill';
    
    public function job() {
    	return $this->belongsTo('Job', 'job_id');
    }
    
}