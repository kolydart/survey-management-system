<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Response
 *
 * @package App
 * @property string $questionnaire
 * @property string $question
 * @property string $answer
 * @property text $content
*/
class Response extends Model
{
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    use SoftDeletes;

    protected $fillable = ['content', 'questionnaire_id', 'question_id', 'answer_id'];
    protected $hidden = [];
    
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setQuestionnaireIdAttribute($input)
    {
        $this->attributes['questionnaire_id'] = $input ? $input : null;
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setQuestionIdAttribute($input)
    {
        $this->attributes['question_id'] = $input ? $input : null;
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setAnswerIdAttribute($input)
    {
        $this->attributes['answer_id'] = $input ? $input : null;
    }
    
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class, 'questionnaire_id')->withTrashed();
    }
    
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id')->withTrashed();
    }
    
    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id')->withTrashed();
    }
    
}
