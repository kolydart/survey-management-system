<?php

namespace App;

use App\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


/**
 * Class Questionnaire
 *
 * @property string $survey
 * @property string $name
 */
class Questionnaire extends Model
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
    /** softCascade */
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['responses'];

    protected $fillable = ['name', 'survey_id'];
    protected $hidden = [];
    protected $appends = ['filled_percent'];

    /**
     * Set to null if empty
     * @param $input
     */
    public function setSurveyIdAttribute($input)
    {
        $this->attributes['survey_id'] = $input ? $input : null;
    }

    public function survey()
    {
        if (request('show_deleted') == 1) {
            return $this->belongsTo(Survey::class, 'survey_id')->withTrashed();
        } else {
            return $this->belongsTo(Survey::class, 'survey_id');
        }
    }

    /**  --- ✄ ----------------------- */
    public function responses()
    {
        if (request('show_deleted') == 1) {
            return $this->hasMany(Response::class, 'questionnaire_id')->withTrashed();
        } else {
            return $this->hasMany(Response::class, 'questionnaire_id');
        }
    }

    /**
     * calculate filled_percent
     * @return decimal  (0.xx)
     */
    public function getFilledPercentAttribute()
    {
        $answered = collect($this->responses->pluck('question_id'))->unique();
        $template = collect($this->survey->items->where('label', '<>', '1')->pluck('question_id'));
        /** protect divide-by-zero */
        if ($template->count() == 0) {
            return false;
        }
        $percent = $answered->intersect($template)->count() / $template->count();

        return number_format((float) $percent, 2, '.', '');
    }

    /**
     * detect outliers in survey design
     * array of answered questions not part of $this->survey->items
     * @return eloquent Question
     */
    public function outliers()
    {
        $answered = collect($this->responses->pluck('question_id'))->unique();
        $template = collect($this->survey->items->where('label', '<>', '1')->pluck('question_id'));

        return Question::find($answered->diff($template)->intersect($answered));
    }

    /**
     * is given question answered in this questionnaire
     * @param  int  $question_id
     * @param  int  $answer_id
     * @return bool
     */
    public function is_question_answered($question_id, $answer_id)
    {
        if (! is_int($question_id) || ! is_int($answer_id)) {
            abort(500, 'wrong input type');
        }
        if ($this->responses->where('answer_id', $answer_id)->where('question_id', $question_id)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
