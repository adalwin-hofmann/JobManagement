<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class CompanyVICreated extends Eloquent {
	
    protected $table = 'company_vi_created';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    public function job() {
        return $this->belongsTo('Job', 'job_id');
    }

    public function template() {
        return $this->belongsTo('CompanyVITemplate', 'template_id');
    }

    public function questionnaire() {
        return $this->belongsTo('Questionnaires', 'questionnaire_id');
    }

    public function responses() {
        return $this->hasMany('CompanyVIResponse', 'cvc_id');
    }

    public function shares() {
        return $this->hasMany('AgencyShare', 'interview_id');
    }

    public function sharedBy($agencyId) {
        if ($this->shares()->where('agency_id', $agencyId)->get()->count() > 0) {
            return true;
        }
        return false;
    }

    public function hasNotes() {
        $flag = false;
        foreach ($this->responses as $item) {
            if ($item->notes()->count() > 0) {
                $flag = true;
            }
        }
        return $flag;
    }
}