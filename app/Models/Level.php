<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code', 'name'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function article()
    {
        return $this->hasMany('App\Models\Article', 'level_id');
    }

    public function user()
    {
        return $this->hasMany('App\Models\User', 'level_id');
    }
}
