<?php
namespace App;

use App\Survey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 *
 * @package App
 * @property string $title
*/
class Category extends Model
{
	/** activity log */
	use \Spatie\Activitylog\Traits\LogsActivity;
	protected static $logFillable = true;
	protected static $logOnlyDirty = true;

    use SoftDeletes;

    protected $fillable = ['title'];
    protected $hidden = [];
    
    /**  --- ✄ ----------------------- */
    
    public function surveys()
    {
        return $this->belongsToMany(Survey::class, 'category_survey')->withTrashed();
    }
    
    
}
