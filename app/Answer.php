<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Answer
 *
 * @package App
 * @property string $title
*/
class Answer extends Model
{
    use SoftDeletes;

    
    protected $fillable = ['title'];
    

    public static function boot()
    {
        parent::boot();

        Answer::observe(new \App\Observers\UserActionsObserver);
    }

    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|required',
            'answerlists' => 'array|required',
            'answerlists.*' => 'integer|exists:answerlists,id|max:4294967295|required'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|required',
            'answerlists' => 'array|required',
            'answerlists.*' => 'integer|exists:answerlists,id|max:4294967295|required'
        ];
    }

    

    
    
    public function answerlists()
    {
        return $this->belongsToMany(Answerlist::class, 'answer_answerlist')->withTrashed();
    }
    
    
}
