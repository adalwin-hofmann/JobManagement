<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Benefits extends Eloquent {
    
    protected $table = 'benefits';
    
    public function job() {
    	return $this->belongsTo('Job', 'job_id');
    }
    
}