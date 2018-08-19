<?php
namespace App;

use App\Answerlist;
use App\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Answer
 *
 * @package App
 * @property string $title
 * @property tinyInteger $open
*/
class Answer extends Model
{
	/** activity log */
	use \Spatie\Activitylog\Traits\LogsActivity;
	protected static $logFillable = true;
	protected static $logOnlyDirty = true;

    /** softCascade */
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['responses'];

    use SoftDeletes;

    protected $fillable = ['title', 'open'];
    protected $hidden = [];


    
    /**  --- ✄ ----------------------- */

    public function answerlists()
    {
        if(request('show_deleted') == 1)
            return $this->belongsToMany(Answerlist::class, 'answer_answerlist')->withTrashed();
        else
            return $this->belongsToMany(Answerlist::class, 'answer_answerlist');
    }
    
    public function responses()
    {
        if(request('show_deleted') == 1)
            return $this->hasMany(Response::class, 'answer_id')->withTrashed();
        else
            return $this->hasMany(Response::class, 'answer_id');
    }
    
    
    
}
