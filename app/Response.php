<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Kolydart\Laravel\App\Traits\Auditable;


/**
 * Class Response
 *
 * @property string $questionnaire
 * @property string $question
 * @property string $answer
 * @property text $content
 */
class Response extends Model
{
    use HasFactory;
    /** activity log */
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ;
    }
    
    /** kolydart auditable */
    use Auditable;    

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
        if (request('show_deleted') == 1) {
            return $this->belongsTo(Questionnaire::class, 'questionnaire_id')->withTrashed();
        } else {
            return $this->belongsTo(Questionnaire::class, 'questionnaire_id');
        }
    }

    public function question()
    {
        if (request('show_deleted') == 1) {
            return $this->belongsTo(Question::class, 'question_id')->withTrashed();
        } else {
            return $this->belongsTo(Question::class, 'question_id');
        }
    }

    public function answer()
    {
        if (request('show_deleted') == 1) {
            return $this->belongsTo(Answer::class, 'answer_id')->withTrashed();
        } else {
            return $this->belongsTo(Answer::class, 'answer_id');
        }
    }
}
