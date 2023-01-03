<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryChapterLike extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'entry_chapter_id',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'entry_chapter_id' => 'integer',
        'user_id' => 'integer'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function entrychapter()
    {
        return $this->belongsTo('App\Models\EntryChapter', 'entry_chapter_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
