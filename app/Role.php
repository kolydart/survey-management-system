<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @package App
 * @property string $title
*/
class Role extends Model
{
	/** activity log */
	use \Spatie\Activitylog\Traits\LogsActivity;
	protected static $logFillable = true;
	protected static $logOnlyDirty = true;

    protected $fillable = ['title'];
    protected $hidden = [];
    
    
    
}
