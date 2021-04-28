<?php

namespace App;

use App\Survey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 *
 * @property string $title
 */
class Category extends Model
{
    use HasFactory;
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

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
