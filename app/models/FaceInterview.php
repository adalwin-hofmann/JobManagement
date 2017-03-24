<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class FaceInterview extends Eloquent {
    
	
    protected $table = 'face_interview';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
}