<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Question
 *
 * @package App
 * @property string $title
 * @property string $answerlist
*/
class Question extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'answerlist_id'];
    protected $hidden = [];
    
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setAnswerlistIdAttribute($input)
    {
        $this->attributes['answerlist_id'] = $input ? $input : null;
    }
    
    public function answerlist()
    {
        return $this->belongsTo(Answerlist::class, 'answerlist_id')->withTrashed();
    }
    
}
