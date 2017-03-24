<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class HintNote extends Eloquent {
    
	
    protected $table = 'hint_note';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function recommend() {
    	return $this->belongsTo('JobRecommend', 'recommend_id');
    }
    
}