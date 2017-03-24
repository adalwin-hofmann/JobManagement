<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyShareNote extends Eloquent {
    
    protected $table = 'company_share_note';

    public function agencyShare() {
        return $this->belongsTo('AgencyShare', 'share_id');
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

}