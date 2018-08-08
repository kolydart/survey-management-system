<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Activitylog
 *
 * @package App
 * @property string $log_name
 * @property string $causer_type
 * @property integer $causer_id
 * @property string $description
 * @property string $subject_type
 * @property integer $subject_id
 * @property text $properties
*/
class Activitylog extends Model
{
    protected $table = 'activity_log';
    
    use SoftDeletes;

    protected $fillable = ['log_name', 'causer_type', 'causer_id', 'description', 'subject_type', 'subject_id', 'properties'];
    protected $hidden = [];
    
    

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setCauserIdAttribute($input)
    {
        $this->attributes['causer_id'] = $input ? $input : null;
    }

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setSubjectIdAttribute($input)
    {
        $this->attributes['subject_id'] = $input ? $input : null;
    }
    
}
