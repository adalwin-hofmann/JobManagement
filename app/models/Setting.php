<?php namespace SH\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Setting extends Eloquent {
    
    protected $table = 'setting';
    
    public static function scopeFindByCode($query, $code) {
        return $query->select('*')
                     ->where('code', '=', $code)
                     ->firstOrFail();
    }
}
