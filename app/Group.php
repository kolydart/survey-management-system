<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
	use SoftDeletes; // trait
	protected $dates = ['deleted_at'];
		
    public function survey(){
    	return $this->belongsTo(Survey::class);
    }
    
}
