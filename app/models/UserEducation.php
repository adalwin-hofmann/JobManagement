<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserEducation extends Eloquent {
    
    protected $table = 'user_education';
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
}