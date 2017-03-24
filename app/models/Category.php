<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Category extends Eloquent {
    
    protected $table = 'category';
    
    public function jobs() {
    	return $this->hasMany('Job', 'category_id');
    }

    public function child() {
        return $this->hasMany('Category', 'parent_id');
    }
    
    public function scopeCalculateCount($query) {
        $tblJob = with(new Job)->getTable();
        $result = $query->select($this->table.'.*', DB::raw("COUNT(*) as cnt"))
                        ->join($tblJob, $tblJob.'.category_id', '=', $this->table.'.id')
                        ->groupBy($this->table.'.id');
        return $result;
    }
    
}