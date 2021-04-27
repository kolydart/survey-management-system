<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Answerlist
 *
 * @property string $title
 * @property string $type
 */
class Answerlist extends Model
{
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    use SoftDeletes;
    /** softCascade */
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['questions'];

    protected $fillable = ['title', 'type'];
    protected $hidden = [];

    public function answers()
    {
        if (request('show_deleted') == 1) {
            return $this->belongsToMany(Answer::class, 'answer_answerlist')->withTrashed();
        } else {
            return $this->belongsToMany(Answer::class, 'answer_answerlist');
        }
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'answerlist_id');
    }
}
