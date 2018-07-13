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
    use SoftDeletes;

    
    protected $fillable = ['name', 'survey_id'];
    

    public static function storeValidation($request)
    {
        return [
            'survey_id' => 'integer|exists:surveys,id|max:4294967295|required',
            'name' => 'max:191|nullable'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'survey_id' => 'integer|exists:surveys,id|max:4294967295|required',
            'name' => 'max:191|nullable'
        ];
    }

    

    
    
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id')->withTrashed();
    }
    
    
}
