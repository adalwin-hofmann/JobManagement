<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserFollowCompany extends Eloquent {
    
    protected $table = 'user_follow_company';
    
    public function followCompany() {
    	return $this->belongsTo('FollowCompany', 'follow_company_id');
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }
    
}