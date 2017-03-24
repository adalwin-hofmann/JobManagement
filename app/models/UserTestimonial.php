<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserTestimonial extends Eloquent {
    
    protected $table = 'user_testimonial';
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
}