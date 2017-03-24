<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class GroupMarketing extends Eloquent {
    
    protected $table = 'group_marketing';
    
    public function group() {
        return $this->belongsTo('Group', 'group_id');
    }

}