<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Survey
 *
 * @package App
 * @property string $title
 * @property string $institution
 * @property string $group
 * @property text $introduction
 * @property text $notes
 * @property string $access
 * @property tinyInteger $completed
*/
class Survey extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'introduction', 'notes', 'access', 'completed', 'institution_id', 'group_id'];
    protected $hidden = [];
    
    

    /**
     * Set to null if empty
     * @param $input
     */
    public function setInstitutionIdAttribute($input)
    {
        $this->attributes['institution_id'] = $input ? $input : null;
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setGroupIdAttribute($input)
    {
        $this->attributes['group_id'] = $input ? $input : null;
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
        return $this->belongsTo(Group::class, 'group_id')->withTrashed();
    }
    
}
