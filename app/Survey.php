<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Survey
 *
 * @package App
 * @property string $title
 * @property string $alias
 * @property string $institution
 * @property text $introduction
 * @property text $javascript
 * @property text $notes
 * @property tinyInteger $inform
 * @property string $access
 * @property tinyInteger $completed
*/
class Survey extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'alias', 'introduction', 'javascript', 'notes', 'inform', 'access', 'completed', 'institution_id'];
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
    
}
