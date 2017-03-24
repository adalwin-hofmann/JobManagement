<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class AgencyClient extends Eloquent {
    
    protected $table = 'agency_client';

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function agency() {
        return $this->belongsTo('Company', 'agency_id');
    }

}