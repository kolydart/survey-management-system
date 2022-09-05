<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

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
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ;
    }    

    protected $fillable = ['title', 'slug'];
    protected $hidden = [];
}
