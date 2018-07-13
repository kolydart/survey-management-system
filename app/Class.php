<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Class
 *
 * @package App
 * @property string $title
*/
class Class extends Model
{
    use SoftDeletes;

    
    protected $fillable = ['title'];
    

    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:classes,title'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:classes,title,'.$request->route('class')
        ];
    }

    

    
    
    
}
