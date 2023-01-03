<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryLike extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'entry_id',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'entry_id' => 'integer',
        'user_id' => 'integer'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function entry()
    {
		return $this->belongsTo('App\Models\Entry', 'entry_id');
    }
}
