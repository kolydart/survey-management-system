<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Answer
 *
 * @package App
 * @property string $title
 * @property tinyInteger $open
*/
class Answer extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'open'];
    protected $hidden = [];
    
    
    
}
