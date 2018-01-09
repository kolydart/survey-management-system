<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function survey(){
    	return $this->belongsTo(Survey::class);
    }
    
}
