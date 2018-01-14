<?php

namespace App;

use App\Answer;
use App\Question;
use App\Survey;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model {
	/**
	 * @return mixed
	 */
	public function survey() {
		return $this->belongsTo(Survey::class);
	}

	public function questions(){
		return $this->belongsToMany(Question::class)->withPivot('answer_id','text');
	}
	
}
