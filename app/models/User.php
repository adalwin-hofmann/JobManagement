<?php

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class User extends Eloquent implements SluggableInterface {

	use SluggableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';
	
	
	protected $sluggable = array(
		'build_from' => 'name',
		'save_to'    => 'slug',
	);
	
	
	public function category() {
		return $this->belongsTo('Category', 'category_id');
	}
	
	public function city() {
		return $this->belongsTo('City', 'city_id');
	}
	
	public function level() {
		return $this->belongsTo('Level', 'level_id');
	}
	
	public function language() {
		return $this->belongsTo('Language', 'native_language_id');
	}

    public function messages() {
        return $this->hasMany('UserMessage', 'user_id');
    }
	
	public function applies() {
		return $this->hasMany('Apply', 'user_id');
	}

    public function hints() {
        return $this->hasMany('JobRecommend', 'user_id');
    }
	
	public function carts() {
		return $this->hasMany('Cart', 'user_id');
	}
	
	public function skills() {
		return $this->hasMany('UserSkill', 'user_id');
	}
	
	public function languages() {
		return $this->hasMany('UserLanguage', 'user_id');
	}
	
	public function educations() {
		return $this->hasMany('UserEducation', 'user_id');
	}

    public function followingCompanies() {
        return $this->hasMany('CompanyUserFollow', 'user_id');
    }
	
	public function awards() {
		return $this->hasMany('UserAwards', 'user_id');
	}

    public function contacts() {
        return $this->hasMany('UserContact', 'user_id');
    }
	
	public function experiences() {
		return $this->hasMany('UserExperience', 'user_id');
	}
	
	public function testimonials() {
		return $this->hasMany('UserTestimonial', 'user_id');
	}

    public function scores() {
        return $this->hasMany('CompanyUserScore', 'user_id');
    }

    public function notes() {
        return $this->hasMany('CompanyUserNote', 'user_id');
    }

    public function labels() {
        return $this->hasMany('UserLabel', 'user_id');
    }

    public function labelIdsOfAgency($agencyId) {
        $labelIds = '';
        foreach ($this->labels()->where('company_id', $agencyId)->get() as $item) {
            if ($labelIds != '') $labelIds .=',';
            $labelIds .= $item->label->id;
        }
        return $labelIds;
    }

    public function invites() {
        return $this->hasMany('CompanyUserInvite', 'user_id');
    }

    public function agencyShares() {
        return $this->hasMany('AgencyShare', 'user_id');
    }
	
	public function getAllColumnsNames()
	{
		switch (DB::connection()->getConfig('driver')) {
			case 'pgsql':
				$query = "SELECT column_name FROM information_schema.columns WHERE table_name = sh_'".$this->table."'";
				$column_name = 'column_name';
				$reverse = true;
				break;
	
			case 'mysql':
				$query = 'SHOW COLUMNS FROM sh_'.$this->table;
				$column_name = 'Field';
				$reverse = false;
				break;
	
			case 'sqlsrv':
				$parts = explode('.', $this->table);
				$num = (count($parts) - 1);
				$table = $parts[$num];
				$query = "SELECT column_name FROM ".DB::connection()->getConfig('database').".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$table."'";
				$column_name = 'column_name';
				$reverse = false;
				break;
	
			default:
				$error = 'Database driver not supported: '.DB::connection()->getConfig('driver');
				throw new Exception($error);
				break;
		}
	
		$columns = array();
	
		foreach(DB::select($query) as $column)
		{
			$columns[] = $column->$column_name;
		}
	
		if($reverse)
		{
			$columns = array_reverse($columns);
		}
	
		return $columns;
	}

    public function age($userId) {

        $prefix = DB::getTablePrefix();

        $sql = "SELECT * FROM ".$prefix.$this->table. " WHERE id=".$userId;

        $result = DB::select($sql);

        $currentDate = date('Y-m-d');

        $d1 = new DateTime($currentDate);
        $d2 = new DateTime($result[0]->birthday);

        return $d2->diff($d1)->y;
    }
    
    public function scopeNewMessages($query, $jobId, $companyId) {
        $tblMessage =with(new Message)->getTable();
        return $this->hasMany('Message', 'user_id')
                    ->where($tblMessage.".job_id", $jobId)
                    ->where($tblMessage.".company_id", $companyId)
                    ->where($tblMessage.".is_read", FALSE)
                    ->where($tblMessage.".is_company_sent", TRUE);
    }

    public function viCreated($companyId) {
        $tblVICreated = with(new CompanyVICreated)->getTable();
        return $this->hasMany('CompanyVICreated', 'user_id')
                    ->where($tblVICreated.".company_id", $companyId);
    }

    
    public function scopeFindByEmail($query, $email) {
        $result = $query->select($this->table.'.*')
                        ->where($this->table.'.email', $email)
                        ->firstOrFail();
        return $result;
    }

    public function isCandidate($companyId) {
        if (CompanyCandidates::where('company_id', $companyId)->where('user_id', $this->id)->get()->count()) {
            return true;
        }
        return false;
    }
    
    public function scopeMatchJobs($query) {
        $tblJob = with(new Job)->getTable();
        
        return DB::table($tblJob)->where('category_id', '=', $this->category_id)
                                 ->where('city_id', '=', $this->city_id)
                                 ->orderBy('id', 'DESC')
                                 ->take(5)
                                 ->get();        
    }


    public function shares() {
        return $this->hasMany('AgencyShare', 'user_id');
    }

    public function sharedBy($agencyId) {
        if ($this->shares()->where('agency_id', $agencyId)->get()->count() > 0) {
            return true;
        }
        return false;
    }

    public function shareNoteByCompany($companyId) {

        $agencyShare = AgencyShare::where('company_id', $companyId)->where('user_id', $this->id)->firstOrFail();

        $note = '';
        if (CompanyShareNote::where('share_id', $agencyShare->id)->where('company_id', $companyId)->get()->count() > 0) {
            $companyShareNote = CompanyShareNote::where('share_id', $agencyShare->id)->where('company_id', $companyId)->firstOrFail();

            if ($companyShareNote) {
                $note = $companyShareNote->note;
            }
        }

        return $note;
    }

}
