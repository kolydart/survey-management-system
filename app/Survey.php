<?php
namespace App;

use App\Item;
use App\Questionnaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Survey
 *
 * @package App
 * @property string $title
 * @property string $institution
 * @property text $introduction
 * @property text $notes
 * @property string $access
 * @property tinyInteger $completed
*/
class Survey extends Model
{
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    use SoftDeletes;

    protected $fillable = ['title', 'introduction', 'notes', 'access', 'completed', 'institution_id'];
    protected $hidden = [];
    
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setInstitutionIdAttribute($input)
    {
        $this->attributes['institution_id'] = $input ? $input : null;
    }
    
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id')->withTrashed();
    }
    
    public function category()
    {
        return $this->belongsToMany(Category::class, 'category_survey')->withTrashed();
    }
    
    public function group()
    {
        return $this->belongsToMany(Group::class, 'group_survey')->withTrashed();
    }
    
    /**  --- ✄ ----------------------- */
    public function questionnaires(){
        return $this->hasMany(Questionnaire::class, 'survey_id')->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'survey_id')->orderByRaw('cast(`order` as decimal)')->withTrashed();
    }

    
}
