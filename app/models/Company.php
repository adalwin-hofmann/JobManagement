<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Company extends Eloquent implements SluggableInterface {
    
	use SluggableTrait;
	
    protected $table = 'company';
    
    protected $sluggable = array(
    		'build_from' => 'name',
    		'save_to'    => 'slug',
    );    
    
    public function city() {
    	return $this->belongsTo('City', 'city_id');
    }
    
    public function teamsize() {
    	return $this->belongsTo('Teamsize', 'teamsize_id');
    }
    
    public function category() {
    	return $this->belongsTo('Category', 'category_id');
    }
    
    public function jobs() {
    	return Job::whereIn('company_id', $this->companyIds());
    }

    public function companyIds() {
        $companyIds = [];

        if ($this->is_admin == 1) {
            $companyIds[] = $this->id;
            foreach ($this->members as $member) {
                $companyIds[] = $member->id;
            }
        }else {

            $companyIds[] = $this->parent->id;
            foreach ($this->parent->members as $member) {
                $companyIds[] = $member->id;
            }
        }

        return $companyIds;
    }

    public function myJobs() {
    	return $this->hasMany('Job', 'company_id');
    }
    
    public function setting() {
        return $this->hasOne('CompanySetting', 'company_id');
    }    

    public function services() {
    	return $this->hasMany('CompanyService', 'company_id');
    }

    public function reviews() {
    	return $this->hasMany('Review', 'company_id');
    }

    public function parent() {
        return $this->belongsTo('Company', 'parent_id');
    }
    
    public function members() {
    	return $this->hasMany('Company', 'parent_id');
    }

    public function followCompanies() {
        return $this->hasMany('FollowCompany', 'company_id');
    }

    public function followUsers() {
        return $this->hasMany('CompanyUserFollow', 'company_id');
    }

    public function userScores() {
        return $this->hasMany('CompanyUserScore', 'company_id');
    }

    public function questions() {
        return $this->hasMany('Questions', 'company_id');
    }

    public function questionnaires() {
        return $this->hasMany('Questionnaires', 'company_id');
    }

    public function templates() {
        return $this->hasMany('CompanyVITemplate', 'company_id');
    }

    public function viCreated() {
        return $this->hasMany('CompanyVICreated', 'company_id');
    }
    
    public function applies() {
        $jobIds = [];
        $jobIds[] = 0;
        foreach ($this->jobs()->get() as $job) {
            $jobIds[] = $job->id;
        }

        return Apply::whereIn('job_id', $jobIds);
    }

    public function invites() {
        return $this->hasMany('CompanyUserInvite', 'company_id');
    }
    
    public function scopeNewMessages($query, $jobId, $userId) {
        $tblMessage =with(new Message)->getTable();
        return $this->hasMany('Message', 'company_id')
                    ->where($tblMessage.".job_id", $jobId)
                    ->where($tblMessage.".user_id", $userId)
                    ->where($tblMessage.".is_read", FALSE)
                    ->where($tblMessage.".is_company_sent", FALSE);
    }

    public function availableJobs($userId) {
        $user = User::find($userId);

        $jobIds = array();
        foreach ($user->applies as $item) {
            $jobIds[] = $item->job_id;
        }

        $invites = $this->invites()->where('user_id', $userId)->get();
        foreach ($invites as $item) {
            $jobIds[] = $item->job_id;
        }

        return $this->jobs()->whereNotIn('id', $jobIds)->where('is_finished', 1)->get();
    }


    public function candidates() {
        return $this->hasMany('CompanyCandidates', 'company_id');
    }

    public function companyApplies() {
        return $this->hasMany('CompanyApply', 'company_id');
    }

    public function agencyShares() {
        return $this->hasMany('AgencyShare', 'agency_id');
    }

    public function companyShares() {
        return $this->hasMany('AgencyShare', 'company_id');
    }

    public function agencies() {
        return $this->hasMany('AgencyClient', 'company_id');
    }

    public function clients() {
        return $this->hasMany('AgencyClient', 'agency_id');
    }

    public function userLabels() {
        return $this->hasMany('UserLabel', 'company_id');
    }

    public function isClient($agencyId) {
        if ($this->agencies()->where('agency_id', $agencyId)->get()->count() > 0) {
            return true;
        }
        return false;
    }

    public function shareNotes() {
        return $this->hasMany('CompanyShareNote', 'company_id');
    }

    public function shareNotesByAgency($agencyId) {
        $shareIds = array();
        $shareIds[] = 0;

        foreach ($this->companyShares()->where('agency_id', $agencyId)->get() as $item) {
            $shareIds[] = $item->id;
        }

        return $this->shareNotes()->whereIn('share_id', $shareIds)->get();
    }

    public function videoInterviews() {
        $cvcIds = array();
        $cvcIds[] = 0;
        foreach ($this->viCreated as $item) {
            if ($item->responses()->count() > 0) {
                $cvcIds[] = $item->id;
            }
        }

        return $this->viCreated()->whereIn('id', $cvcIds)->get();
    }
}