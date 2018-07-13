<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Institution
 *
 * @package App
 * @property string $title
*/
class Institution extends Model
{
    use SoftDeletes;

    
    protected $fillable = ['title'];
    

    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:institutions,title'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|required|unique:institutions,title,'.$request->route('institution')
        ];
    }

    

    
    
    
}
