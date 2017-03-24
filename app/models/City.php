<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class City extends Eloquent {
    
    protected $table = 'city';
    
    public function country() {
    	return $this->belongsTo('Country', 'country_id');
    }
    
}