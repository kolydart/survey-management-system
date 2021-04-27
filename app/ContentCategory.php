<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentCategory
 *
 * @property string $title
 * @property string $slug
 */
class ContentCategory extends Model
{
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = ['title', 'slug'];
    protected $hidden = [];
}
