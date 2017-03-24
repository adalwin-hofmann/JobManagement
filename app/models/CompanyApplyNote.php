<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyApplyNote extends Eloquent {
    
	
    protected $table = 'company_apply_note';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function apply() {
    	return $this->belongsTo('CompanyApply', 'apply_id');
    }
    
}