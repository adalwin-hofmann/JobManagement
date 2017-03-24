<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class UserLabel extends Eloquent {
    
    protected $table = 'user_label';

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    public function label() {
        return $this->belongsTo('Label', 'label_id');
    }
}