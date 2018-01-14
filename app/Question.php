<?php

namespace App;

use App\Answer;
use App\Questionnaire;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function surveys(){
    	return $this->belongsToMany(Survey::class);
    }

    public function questionnaires(){
    	return $this->belongsToMany(Questionnaire::class)->withPivot('answer_id', 'response');
    }
    
    public function answers(){
    	return $this->belongsToMany(Answer::class);
    }
    
}
