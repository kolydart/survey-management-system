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
*/
class Survey extends Model
{
    use SoftDeletes;

    
    protected $fillable = ['title', 'institution_id', 'group_id'];
    

    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|required',
            'institution_id' => 'integer|exists:institutions,id|max:4294967295|nullable',
            'category' => 'array|nullable',
            'category.*' => 'integer|exists:categories,id|max:4294967295|nullable',
            'group_id' => 'integer|exists:groups,id|max:4294967295|nullable'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|required',
            'institution_id' => 'integer|exists:institutions,id|max:4294967295|nullable',
            'category' => 'array|nullable',
            'category.*' => 'integer|exists:categories,id|max:4294967295|nullable',
            'group_id' => 'integer|exists:groups,id|max:4294967295|nullable'
        ];
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
