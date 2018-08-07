<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Answerlist
 *
 * @package App
 * @property string $title
 * @property string $type
*/
class Answerlist extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'type'];
    protected $hidden = [];
    
    
    
}