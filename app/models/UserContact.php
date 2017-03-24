<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserContact extends Eloquent {
    
    protected $table = 'user_contact';
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
}