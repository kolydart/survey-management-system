<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Class Item
 *
 * @property string $survey
 * @property string $question
 * @property tinyInteger $label
 * @property string $order
 */
class Item extends Model
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
        if (request('show_deleted') == 1) {
            return $this->belongsTo(Survey::class, 'survey_id')->withTrashed();
        } else {
            return $this->belongsTo(Survey::class, 'survey_id');
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
}
