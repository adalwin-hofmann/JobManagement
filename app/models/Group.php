<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Group extends Eloquent {
    
    protected $table = 'group';
    
    public function groupCompanies() {
        return $this->hasMany('GroupCompany', 'group_id');
    }
    
    public function groupMarketings() {
        return $this->hasMany('GroupMarketing', 'group_id');
    }

}