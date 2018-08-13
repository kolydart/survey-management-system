<?php
namespace App;

use App\Item;
use App\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Question
 *
 * @package App
 * @property string $title
 * @property string $answerlist
*/
class Question extends Model
{
	/** activity log */
	use \Spatie\Activitylog\Traits\LogsActivity;
	protected static $logFillable = true;
	protected static $logOnlyDirty = true;

    use SoftDeletes;

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
        return $this->belongsTo(Answerlist::class, 'answerlist_id')->withTrashed();
    }

    /**  --- ✄ ----------------------- */

    public function items()
    {
        return $this->hasMany(Item::class, 'question_id')->withTrashed();
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'question_id')->withTrashed();
    }
    


    
}
