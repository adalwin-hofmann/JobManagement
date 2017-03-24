<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyVITemplate extends Eloquent {
	
    protected $table = 'company_vi_template';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }

}