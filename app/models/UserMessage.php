<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserMessage extends Eloquent {
    
    protected $table = 'user_message';
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }

    public  function job() {
        return $this->belongsTo('Job', 'user_id');
    }
    
}