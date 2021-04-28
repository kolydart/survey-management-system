<?php

namespace App;

use App\Item;
use App\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Question
 *
 * @property string $title
 * @property string $answerlist
 */
class Question extends Model
{
    use HasFactory;
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    use SoftDeletes;
    /** softCascade */
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['items', 'responses'];

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
        if (request('show_deleted') == 1) {
            return $this->belongsTo(Answerlist::class, 'answerlist_id')->withTrashed();
        } else {
            return $this->belongsTo(Answerlist::class, 'answerlist_id');
        }
    }

    /**  --- ✄ ----------------------- */
    public function items()
    {
        if (request('show_deleted') == 1) {
            return $this->hasMany(Item::class, 'question_id')->withTrashed();
        } else {
            return $this->hasMany(Item::class, 'question_id');
        }
    }

    public function responses()
    {
        if (request('show_deleted') == 1) {
            return $this->hasMany(Response::class, 'question_id')->withTrashed();
        } else {
            return $this->hasMany(Response::class, 'question_id');
        }
    }
}
