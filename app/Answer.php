<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
	use SoftDeletes; // trait
	protected $dates = ['deleted_at'];
		
    public function questions(){
    	return $this->belongsToMany(Question::class);
    }
    
}
