<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    public function groups(){
    	return $this->hasMany(Group::class);
    }
    
}
