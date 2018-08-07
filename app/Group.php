<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Group
 *
 * @package App
 * @property string $title
*/
class Group extends Model
{
    use SoftDeletes;

    protected $fillable = ['title'];
    protected $hidden = [];
    
    
    
}
