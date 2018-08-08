<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Questionnaire
 *
 * @package App
 * @property string $survey
 * @property string $name
*/
class Questionnaire extends Model
{
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    use SoftDeletes;

    protected $fillable = ['name', 'survey_id'];
    protected $hidden = [];
    
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setSurveyIdAttribute($input)
    {
        $this->attributes['survey_id'] = $input ? $input : null;
    }
    
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id')->withTrashed();
    }
    
}
