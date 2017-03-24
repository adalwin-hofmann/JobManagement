<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserExperience extends Eloquent {
    
    protected $table = 'user_experience';
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
    public function type() {
    	return $this->belongsTo('Type', 'type_id');
    }
    
}