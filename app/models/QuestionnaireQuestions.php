<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class QuestionnaireQuestions extends Eloquent {
	
    protected $table = 'questionnaire_questions';
    
    public function questionnaires() {
    	return $this->belongsTo('Questionnaires', 'questionnaires_id');
    }

    public function questions() {
        return $this->belongsTo('Questions', 'questions_id');
    }

}