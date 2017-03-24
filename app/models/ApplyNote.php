<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ApplyNote extends Eloquent {
    
	
    protected $table = 'apply_note';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function apply() {
    	return $this->belongsTo('Apply', 'apply_id');
    }
    
}