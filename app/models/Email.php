<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Email extends Eloquent {
    
    protected $table = 'email';
    
    public static function scopeFindByCode($query, $code) {
        return $query->select('*')->where('code', '=', $code)->firstOrFail();
    }

}