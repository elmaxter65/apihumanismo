<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryLanguage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'entry_id',
        'language_id',
        'title',
        'subtitle',
        'video_transcription',
        'content',
        'meta_description',
        'seo_title',
        'slug',
        'h1',
        'h2',
        'h3',
        'h4',
        'url_video_youtube',
        'url_video_vimeo',
        'url_audio'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'entry_id' => 'integer',
        'language_id' => 'integer'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function entry()
    {
        return $this->belongsTo('App\Models\Entry', 'entry_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

}
