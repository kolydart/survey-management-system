<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ContentTag
 *
 * @property string $title
 * @property string $slug
 */
class ContentTag extends Model
{
    use HasFactory;
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    protected $fillable = ['title', 'slug'];
    protected $hidden = [];
}
