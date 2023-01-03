<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryChapterLanguage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'entry_chapter_id',
        'language_id',
        'title',
        'content',
        'slug',
        'url_video'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'entry_chapter_id' => 'integer',
        'language_id' => 'integer'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function entrychapter()
    {
        return $this->belongsTo('App\Models\EntryChapter', 'entry_chapter_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

}
