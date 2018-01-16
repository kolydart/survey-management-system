<?php

namespace App;

use App\Answer;
use App\Questionnaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes; // trait
    protected $dates = ['deleted_at'];
        
    public function surveys(){
    	return $this->belongsToMany(Survey::class);
    }

    public function questionnaires(){
    	return $this->belongsToMany(Questionnaire::class)->withPivot('answer_id', 'response');
    }
    
    public function answers(){
    	return $this->belongsToMany(Answer::class);
    }
    
    /**
     * Which answer(s) was submitted
     * @param  Questionnaire $questionnaire 
     * @return collection
     */
    public function answered(Questionnaire $questionnaire){
        return $this->answers->where('order',$this->questionnaires->find($questionnaire)->pivot->answer_id);
    }

}
