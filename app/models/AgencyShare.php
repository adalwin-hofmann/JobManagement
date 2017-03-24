<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class AgencyShare extends Eloquent {
    
    protected $table = 'agency_share';

    public function job() {
        return $this->belongsTo('Job', 'job_id');
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function agency() {
        return $this->belongsTo('Company', 'agency_id');
    }

    public function interview() {
        return $this->belongsTo('CompanyVICreated', 'interview_id');
    }


    public function notes() {
        return $this->hasMany('CompanyShareNote', 'share_id');
    }

    public function noteByCompany($companyId) {
        if ($this->notes()->where('company_id', $companyId)->get()->count() == 0) {
            return '';
        }

        $shareNote = $this->notes()->where('company_id', $companyId)->firstOrFail();
        return $shareNote->note;
    }

}