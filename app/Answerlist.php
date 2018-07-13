<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Answerlist
 *
 * @package App
 * @property string $title
 * @property string $type
*/
class Answerlist extends Model
{
    use SoftDeletes;

    
    protected $fillable = ['title', 'type'];
    

    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:answerlists,title',
            'type' => 'in:Radio,Radio + Text,Checkbox,Checkbox + Text,Text|max:191|required',
            'answers' => 'array|required',
            'answers.*' => 'integer|exists:answers,id|max:4294967295|required'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:answerlists,title,'.$request->route('answerlist'),
            'type' => 'in:Radio,Radio + Text,Checkbox,Checkbox + Text,Text|max:191|required',
            'answers' => 'array|required',
            'answers.*' => 'integer|exists:answers,id|max:4294967295|required'
        ];
    }

    

    
    
    public function answers()
    {
        return $this->belongsToMany(Answer::class, 'answer_answerlist')->withTrashed();
    }
    
    
}
