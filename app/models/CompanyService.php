<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyService extends Eloquent {
    
	
    protected $table = 'company_service';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function service() {
    	return $this->belongsTo('Service', 'service_id');
    }
    
}