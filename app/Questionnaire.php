<?php

namespace App;

use App\Answer;
use App\Question;
use App\Survey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionnaire extends Model {
	use SoftDeletes; // trait
	protected $dates = ['deleted_at'];
		
	/**
	 * @return mixed
	 */
	public function survey() {
		return $this->belongsTo(Survey::class);
	}

	public function questions(){
		return $this->belongsToMany(Question::class)->withPivot('answer_order','response');
	}

}
