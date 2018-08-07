<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Answer
 *
 * @package App
 * @property string $title
*/
class Answer extends Model
{
    use SoftDeletes;

    protected $fillable = ['title'];
    protected $hidden = [];
    
    
    
    public function answerlists()
    {
        return $this->belongsToMany(Answerlist::class, 'answer_answerlist')->withTrashed();
    }
    
}