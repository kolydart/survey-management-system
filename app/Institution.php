<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Institution
 *
 * @package App
 * @property string $title
*/
class Institution extends Model
{
    use SoftDeletes;

    protected $fillable = ['title'];
    protected $hidden = [];
    
    
    
}
