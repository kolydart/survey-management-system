<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentCategory
 *
 * @package App
 * @property string $title
 * @property string $slug
*/
class ContentCategory extends Model
{
    
    protected $fillable = ['title', 'slug'];
    

    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|nullable',
            'slug' => 'max:191|nullable'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|nullable',
            'slug' => 'max:191|nullable'
        ];
    }

    

    
    
    
}
