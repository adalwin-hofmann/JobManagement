<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Questionnaires extends Eloquent {
	
    protected $table = 'questionnaires';
    
    public function company() {
    	return $this->belongsTo('Company', 'company_id');
    }

    public function questions() {
        return $this->hasMany('QuestionnaireQuestions', 'questionnaires_id');
    }

}