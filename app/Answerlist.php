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
    

    public static function boot()
    {
        parent::boot();

        Answerlist::observe(new \App\Observers\UserActionsObserver);
    }

    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:answerlists,title',
            'type' => 'in:Radio,Radio + Text,Checkbox,Checkbox + Text,Text|max:191|required'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:answerlists,title,'.$request->route('answerlist'),
            'type' => 'in:Radio,Radio + Text,Checkbox,Checkbox + Text,Text|max:191|required'
        ];
    }

    

    
    
    
}
