<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryTag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'entry_id',
        'tag_id',
        'language_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'entry_id' => 'integer',
        'tag_id' => 'integer',
        'language_id' => 'integer'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function entry()
    {
        return $this->belongsTo('App\Models\Entry', 'entry_id');
    }

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag', 'tag_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
}
