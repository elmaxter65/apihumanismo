<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryChapter extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'entry_id',
        'order',
        'likes_number'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'entry_id' => 'integer',
        'order' => 'integer',
        'likes_number' => 'integer'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function entry()
    {
        return $this->belongsTo('App\Models\Entry', 'entry_id');
    }

    public function entrychapterlanguage()
    {
        return $this->hasMany('App\Models\EntryChapterLanguage', 'entry_chapter_id');
    }

    public function entrychapterusers()
    {
        return $this->hasMany('App\Models\EntryChapterUser', 'entry_chapter_id');
    }

    public function entrychapterlikes()
    {
        return $this->hasMany('App\Models\EntryChapterLike', 'entry_chapter_id');
    }

}
