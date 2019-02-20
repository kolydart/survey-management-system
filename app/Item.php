<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Item
 *
 * @package App
 * @property string $survey
 * @property string $question
 * @property tinyInteger $label
 * @property string $order
*/
class Item extends Model
{
    use SoftDeletes;

    protected $fillable = ['label', 'order', 'survey_id', 'question_id'];
    protected $hidden = [];
    
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setSurveyIdAttribute($input)
    {
        $this->attributes['survey_id'] = $input ? $input : null;
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setQuestionIdAttribute($input)
    {
        $this->attributes['question_id'] = $input ? $input : null;
    }
    
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id')->withTrashed();
    }
    
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id')->withTrashed();
    }
    
}
