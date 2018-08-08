<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Institution
 *
 * @package App
 * @property string $title
*/
class Institution extends Model
{
	/** activity log */
	use \Spatie\Activitylog\Traits\LogsActivity;
	protected static $logFillable = true;
	protected static $logOnlyDirty = true;

    use SoftDeletes;

    protected $fillable = ['title'];
    protected $hidden = [];
    
    
    
    public function surveys() {
        return $this->hasMany(Survey::class, 'institution_id');
    }
}
