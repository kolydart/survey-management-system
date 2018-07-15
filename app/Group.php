<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Group
 *
 * @package App
 * @property string $title
*/
class Group extends Model
{
    use SoftDeletes;

    
    protected $fillable = ['title'];
    

    public static function boot()
    {
        parent::boot();

        Group::observe(new \App\Observers\UserActionsObserver);
    }

    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:groups,title'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:groups,title,'.$request->route('group')
        ];
    }

    

    
    
    
}
