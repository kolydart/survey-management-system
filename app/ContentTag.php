<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentTag
 *
 * @package App
 * @property string $title
 * @property string $slug
*/
class ContentTag extends Model
{
    
    protected $fillable = ['title', 'slug'];
    

    public static function boot()
    {
        parent::boot();

        ContentTag::observe(new \App\Observers\UserActionsObserver);
    }

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
