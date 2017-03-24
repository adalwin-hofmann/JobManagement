<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserLanguage extends Eloquent {
    
    protected $table = 'user_language';
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
    
    public function language() {
    	return $this->belongsTo('Language', 'language_id');
    }
}