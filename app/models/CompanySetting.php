<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanySetting extends Eloquent {
	
    protected $table = 'company_setting';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
}