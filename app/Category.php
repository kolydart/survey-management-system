<?php

namespace App;

use App\Survey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Class Category
 *
 * @property string $title
 */
class Category extends Model
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

    use SoftDeletes;

    protected $fillable = ['title'];
    protected $hidden = [];

    /**  --- ✄ ----------------------- */
    public function surveys()
    {
        if (request('show_deleted') == 1) {
            return $this->belongsToMany(Survey::class, 'category_survey')->withTrashed();
        } else {
            return $this->belongsToMany(Survey::class, 'category_survey');
        }
    }
}
