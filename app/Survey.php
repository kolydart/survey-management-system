<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
	use SoftDeletes; // trait
	protected $dates = ['deleted_at'];
		
    public function groups(){
    	return $this->hasMany(Group::class);
    }

    public function questions(){
    	return $this->belongsToMany(Question::class)->withPivot('code')->orderBy('code');
    }
    
}
