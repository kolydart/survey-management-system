<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Item
 *
 * @package App
 * @property string $survey
 * @property string $question
 * @property string $order
*/
class Item extends Model
{
    use SoftDeletes;

    
    protected $fillable = ['order', 'survey_id', 'question_id'];
    

    public static function boot()
    {
        parent::boot();

        Item::observe(new \App\Observers\UserActionsObserver);
    }

    public static function storeValidation($request)
    {
        return [
            'survey_id' => 'integer|exists:surveys,id|max:4294967295|required',
            'question_id' => 'integer|exists:questions,id|max:4294967295|required',
            'order' => 'max:191|nullable'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'survey_id' => 'integer|exists:surveys,id|max:4294967295|required',
            'question_id' => 'integer|exists:questions,id|max:4294967295|required',
            'order' => 'max:191|nullable'
        ];
    }

    

    
    
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id')->withTrashed();
    }
    
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id')->withTrashed();
    }
    
    
}
