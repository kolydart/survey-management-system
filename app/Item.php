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
 * @property string $order
*/
class Item extends Model
{
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    use SoftDeletes;

    protected $fillable = ['order', 'survey_id', 'question_id'];
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
        if(request('show_deleted') == 1)
            return $this->belongsTo(Survey::class, 'survey_id')->withTrashed();
        else
            return $this->belongsTo(Survey::class, 'survey_id');
    }
    
    public function question()
    {
        if(request('show_deleted') == 1)
            return $this->belongsTo(Question::class, 'question_id')->withTrashed();
        else
            return $this->belongsTo(Question::class, 'question_id');
    }
    
}
