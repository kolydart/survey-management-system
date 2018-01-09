<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    public function groups(){
    	return $this->hasMany(Group::class);
    }

    public function questions(){
    	return $this->belongsToMany(Question::class)->withPivot('code')->orderBy('code');
    }
    
}
