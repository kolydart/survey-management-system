<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Response
 *
 * @package App
 * @property string $question
 * @property text $content
 * @property string $answer
*/
class Response extends Model
{
    use SoftDeletes;

    
    protected $fillable = ['content', 'question_id', 'answer_id'];
    

    public static function boot()
    {
        parent::boot();

        Response::observe(new \App\Observers\UserActionsObserver);
    }

    public static function storeValidation($request)
    {
        return [
            'question_id' => 'integer|exists:questions,id|max:4294967295|required',
            'content' => 'max:65535|nullable',
            'answer_id' => 'integer|exists:answers,id|max:4294967295|required'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'question_id' => 'integer|exists:questions,id|max:4294967295|required',
            'content' => 'max:65535|nullable',
            'answer_id' => 'integer|exists:answers,id|max:4294967295|required'
        ];
    }

    

    
    
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id')->withTrashed();
    }
    
    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id')->withTrashed();
    }
    
    
}
