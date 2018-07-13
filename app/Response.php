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
*/
class Response extends Model
{
    use SoftDeletes;

    
    protected $fillable = ['content', 'question_id'];
    

    public static function storeValidation($request)
    {
        return [
            'question_id' => 'integer|exists:questions,id|max:4294967295|required',
            'content' => 'max:65535|nullable'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'question_id' => 'integer|exists:questions,id|max:4294967295|required',
            'content' => 'max:65535|nullable'
        ];
    }

    

    
    
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id')->withTrashed();
    }
    
    
}
