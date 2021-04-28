<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Loguseragent
 *
 * @property string $os
 * @property string $os_version
 * @property string $browser
 * @property string $browser_version
 * @property string $device
 * @property string $language
 * @property int $item_id
 * @property string $ipv6
 * @property string $uri
 * @property tinyInteger $form_submitted
 * @property string $user
 */
class Loguseragent extends Model
{
    use HasFactory;

    protected $fillable = ['os', 'os_version', 'browser', 'browser_version', 'device', 'language', 'item_id', 'ipv6', 'uri', 'form_submitted', 'user_id'];
    protected $hidden = [];

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setItemIdAttribute($input)
    {
        $this->attributes['item_id'] = $input ? $input : null;
    }

    /**
     * Set to null if empty
     * @param $input
     */
    public function setUserIdAttribute($input)
    {
        $this->attributes['user_id'] = $input ? $input : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
