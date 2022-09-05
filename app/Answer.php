<?php

namespace App;

use App\Answerlist;
use App\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Class Answer
 *
 * @property string $title
 * @property tinyInteger $open
 */
class Answer extends Model
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

    /** softCascade */
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['responses'];

    use SoftDeletes;

    protected $fillable = ['title', 'open', 'hidden'];
    protected $hidden = [];

    /**  --- ✄ ----------------------- */
    public function answerlists()
    {
        if (request('show_deleted') == 1) {
            return $this->belongsToMany(Answerlist::class, 'answer_answerlist')->withTrashed();
        } else {
            return $this->belongsToMany(Answerlist::class, 'answer_answerlist');
        }
    }

    public function responses()
    {
        if (request('show_deleted') == 1) {
            return $this->hasMany(Response::class, 'answer_id')->withTrashed();
        } else {
            return $this->hasMany(Response::class, 'answer_id');
        }
    }

    /**
     * first hidden answer
     * @param  query $query
     * @return obj   single hidden answer
     *
     * @example
     * $answer->hidden->id
     */
    public function scopeHidden($query)
    {
        return $query->where('hidden', 1)->first();
    }
}
