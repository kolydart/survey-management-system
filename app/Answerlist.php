<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Answerlist
 *
 * @package App
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

    protected $fillable = ['title', 'type'];
    protected $hidden = [];
    
    
    
    public function questions() {
        return $this->hasMany(Question::class, 'answerlist_id');
    }
}
