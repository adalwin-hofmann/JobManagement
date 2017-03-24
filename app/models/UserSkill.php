<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserSkill extends Eloquent {
    
    protected $table = 'user_skill';
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
}