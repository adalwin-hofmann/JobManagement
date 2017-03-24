<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class GroupCompany extends Eloquent {
    
    protected $table = 'group_company';
    
    public function group() {
        return $this->belongsTo('Group', 'group_id');
    }
    
    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

}