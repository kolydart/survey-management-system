<?php

namespace App;

use App\Item;
use App\Questionnaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Survey
 *
 * @property string $title
 * @property string $alias
 * @property string $institution
 * @property text $introduction
 * @property text $javascript
 * @property text $notes
 * @property tinyInteger $inform
 * @property string $access
 * @property tinyInteger $completed
 */
class Survey extends Model
{
    /** activity log */
    use \Spatie\Activitylog\Traits\LogsActivity;
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    use SoftDeletes;
    /** softCascade */
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['questionnaires', 'items'];

    protected $fillable = ['title', 'alias', 'introduction', 'javascript', 'notes', 'inform', 'access', 'completed', 'institution_id'];
    protected $hidden = [];

    /**
     * Set to null if empty
     * @param $input
     */
    public function setInstitutionIdAttribute($input)
    {
        $this->attributes['institution_id'] = $input ? $input : null;
    }

    public function institution()
    {
        if (request('show_deleted') == 1) {
            return $this->belongsTo(Institution::class, 'institution_id')->withTrashed();
        } else {
            return $this->belongsTo(Institution::class, 'institution_id');
        }
    }

    public function category()
    {
        if (request('show_deleted') == 1) {
            return $this->belongsToMany(Category::class, 'category_survey')->withTrashed();
        } else {
            return $this->belongsToMany(Category::class, 'category_survey');
        }
    }

    public function group()
    {
        if (request('show_deleted') == 1) {
            return $this->belongsToMany(Group::class, 'group_survey')->withTrashed();
        } else {
            return $this->belongsToMany(Group::class, 'group_survey');
        }
    }

    /**  --- ✄ ----------------------- */
    public function questionnaires()
    {
        if (request('show_deleted') == 1) {
            return $this->hasMany(Questionnaire::class, 'survey_id')->withTrashed();
        } else {
            return $this->hasMany(Questionnaire::class, 'survey_id');
        }
    }

    public function items()
    {
        if (request('show_deleted') == 1) {
            return $this->hasMany(Item::class, 'survey_id')->orderByRaw('cast(`order` as decimal)')->withTrashed();
        } else {
            return $this->hasMany(Item::class, 'survey_id')->orderByRaw('cast(`order` as decimal)');
        }
    }
}
