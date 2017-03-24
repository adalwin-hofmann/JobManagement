<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Questions extends Eloquent {
	
    protected $table = 'questions';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }

}