<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Job extends Eloquent implements SluggableInterface {
    
	use SluggableTrait;
	
    protected $table = 'job';
    
    protected $sluggable = array(
    		'build_from' => 'name',
    		'save_to'    => 'slug',
    );
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }
    
    public function level() {
    	return $this->belongsTo('Level', 'level_id');
    }
    
    public function city() {
    	return $this->belongsTo('City', 'city_id');
    }
    
    public function category() {
    	return $this->belongsTo('Category', 'category_id');
    }
    
    public function presence() {
    	return $this->belongsTo('Presence', 'presence_id');
    }
    
    public function language() {
    	return $this->belongsTo('Language', 'native_language_id');
    }
    
    public function foreignLanguages() {
    	return $this->hasMany('JobLanguage', 'job_id');
    }
    
    public function type() {
    	return $this->belongsTo('Type', 'type_id');
    }
    
    public function skills() {
    	return $this->hasMany('JobSkill', 'job_id');
    }
    
    public function applies() {
    	return $this->hasMany('Apply', 'job_id');
    }   
    
    public function lastApply() {
    	return $this->applies()->getQuery()->orderBy('created_at', 'desc')->firstOrFail();
    }
    
    public function hints() {
    	return $this->hasMany('JobRecommend', 'job_id');
    }
    
    public function benefits() {
    	return $this->hasMany('Benefits', 'job_id');
    }

    public function invites() {
        return $this->hasMany('CompanyUserInvite', 'job_id');
    }
    
    public function cvcs() {
        return $this->hasMany('CompanyVICreated', 'job_id');
    }    
    
    public function messages($userId) {
        $tblMessage =with(new Message)->getTable();
        return $this->hasMany('Message', 'job_id')
                    ->where($tblMessage.'.user_id', $userId)
                    ->orderBy('created_at', 'DESC');
    }
    
    public function scopeSimilar($query) {
        return $query->select($this->table.'.*')
                        ->where($this->table.'.category_id', '=', $this->category_id)
                        ->where($this->table.'.city_id', '=', $this->city_id)
                        ->where($this->table.'.id', '<>', $this->id)
                        ->orderBy('id', 'DESC')
                        ->take(5)
                        ->get();
    }

    public function shares() {
        return $this->hasMany('AgencyShare', 'job_id');
    }

    public function sharedBy($agencyId) {
        if ($this->shares()->where('agency_id', $agencyId)->get()->count() >0) {
            return true;
        }
        return false;
    }

    public function sharedToCompany($companyId) {
        if ($this->shares()->where('company_id', $companyId)->get()->count() > 0) {
            return true;
        }
        return false;
    }

}