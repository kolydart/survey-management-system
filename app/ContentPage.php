<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class ContentPage
 *
 * @package App
 * @property string $title
 * @property text $page_text
 * @property text $excerpt
 * @property string $featured_image
*/
class ContentPage extends Model implements HasMedia
{
    use HasMediaTrait;

    
    protected $fillable = ['title', 'page_text', 'excerpt'];
    protected $appends = ['featured_image', 'featured_image_link'];
    protected $with = ['media'];
    

    public static function storeValidation($request)
    {
        return [
            'title' => 'max:191|required',
            'category_id' => 'array|nullable',
            'category_id.*' => 'integer|exists:content_categories,id|max:4294967295|nullable',
            'tag_id' => 'array|nullable',
            'tag_id.*' => 'integer|exists:content_tags,id|max:4294967295|nullable',
            'page_text' => 'max:65535|nullable',
            'excerpt' => 'max:65535|nullable',
            'featured_image' => 'file|image|nullable'
        ];
    }

    public static function updateValidation($request)
    {
        return [
            'title' => 'max:191|required',
            'category_id' => 'array|nullable',
            'category_id.*' => 'integer|exists:content_categories,id|max:4294967295|nullable',
            'tag_id' => 'array|nullable',
            'tag_id.*' => 'integer|exists:content_tags,id|max:4294967295|nullable',
            'page_text' => 'max:65535|nullable',
            'excerpt' => 'max:65535|nullable',
            'featured_image' => 'nullable'
        ];
    }

    

    public function getFeaturedImageAttribute()
    {
        return $this->getFirstMedia('featured_image');
    }

    /**
     * @return string
     */
    public function getFeaturedImageLinkAttribute()
    {
        $file = $this->getFirstMedia('featured_image');
        if (! $file) {
            return null;
        }

        return '<a href="' . $file->getUrl() . '" target="_blank">' . $file->file_name . '</a>';
    }
    
    public function category_id()
    {
        return $this->belongsToMany(ContentCategory::class, 'content_category_content_page');
    }
    
    public function tag_id()
    {
        return $this->belongsToMany(ContentTag::class, 'content_page_content_tag');
    }
    
    
}
