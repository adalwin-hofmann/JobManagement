<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class JobRecommend extends Eloquent {
    
    protected $table = 'job_recommend';
    
    public function job() {
    	return $this->belongsTo('Job', 'job_id');
    }
    
    public function user() {
    	return $this->belongsTo('User', 'user_id');
    }
    
    
    public function notes() {
    	return $this->hasMany('HintNote', 'recommend_id');
    }
}