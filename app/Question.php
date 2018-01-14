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

    public function questionnaire(){
    	return $this->belongsToMany(Questionnaire::class);
    }
    
    public function answers(){
    	return $this->belongsToMany(Answer::class);
    }
    
}
