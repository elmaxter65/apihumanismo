<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entry extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'index_content',
        'likes_number',
        'views_number',
        'reading_time',
        'appears_home',
        'author_id',
        'status_id',
        'theme_id',
        'entry_type_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'author_id' => 'integer',
        'status_id' => 'integer',
        'theme_id' => 'integer',
        'entry_type_id' => 'integer',
        'index_content' => 'integer',
        'views_number' => 'integer',
        'reading_time' => 'integer',
        'appears_home' => 'integer',
        'likes_number' => 'integer'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function author()
    {
        return $this->belongsTo('App\Models\User', 'author_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status', 'status_id');
    }

    public function theme()
    {
        return $this->belongsTo('App\Models\Theme', 'theme_id');
    }

    public function entrytype()
    {
        return $this->belongsTo('App\Models\EntryType', 'entry_type_id');
    }

    public function entrylanguage()
    {
        return $this->hasMany('App\Models\EntryLanguage', 'entry_id');
    }

    public function entrylikes()
    {
        return $this->hasMany('App\Models\EntryLike', 'entry_id');
    }

    public function entrytag()
    {
        return $this->hasMany('App\Models\EntryTag', 'entry_id');
    }

    public function entrychapter()
    {
        return $this->hasMany('App\Models\EntryChapter', 'entry_id');
    }

    /****************************************************************/
    /************************* SCOPES *******************************/
    /****************************************************************/

    public function scopeByTitle($query, $title)
    {
        if(($title != '') || ($title != null)) {
            return $query->whereHas('entrylanguage', function($q2) use($title){
                return $q2->where('title', 'LIKE', '%' . trim($title) . '%');
            });
        }
    }

    public function scopeBySubtitle($query, $subtitle)
    {
        if(($subtitle != '') || ($subtitle != null)) {
            return $query->whereHas('entrylanguage', function($q2) use($subtitle){
                return $q2->where('subtitle', 'LIKE', '%' . trim($subtitle) . '%');
            });
        }
    }

    public function scopeByFormat($query, $entry_type_id)
    {
        if(($entry_type_id != '') || ($entry_type_id != null)) {
            if ($entry_type_id >= 1) {
                return $query->where('entry_type_id', '=', $entry_type_id);
            } else {
                return $query->where('entry_type_id', '>', 0);
            }

        } else {
            return $query->where('entry_type_id', '>', 0);
        }
    }

    public function scopeByReadingTime($query, $reading_time)
    {
        if ( count($reading_time) > 0 ) {
            return $query->whereIn('reading_time', $reading_time);
        }
    }

    public function scopeByAppearsHome($query, $appears_home)
    {
        if ( $appears_home == 0 ) {
            return $query->whereNotIn('appears_home', [1]);
        }

        if ( $appears_home == 1 ) {
            return $query->whereIn('appears_home', [1]);
        }
    }

    public function scopeByAuthor($query, $author_id)
    {
        if (($author_id != '') || ($author_id != null)) {
            return $query->where('author_id', '=', $author_id);
        }
    }

    public function scopeByStatus($query, $status_id)
    {
        if (($status_id != '') || ($status_id != null)) {
            return $query->where('status_id', '=', $status_id);
        }
    }

    public function scopeByTheme($query, $theme_id)
    {
        if (($theme_id != '') || ($theme_id != null)) {
            return $query->where('theme_id', '=', $theme_id);
        }
    }

    public function scopeByLanguage($query, $language_id)
    {
        if(($language_id != '') || ($language_id != null)) {
            return $query->whereHas('entrylanguage', function($q2) use($language_id){
                return $q2->where('language_id', '=' , $language_id);
            });
        }
    }

    public function scopeByThemes($query, $themes)
    {
        if ( count($themes) > 0 ) {
            return $query->whereIn('theme_id', $themes);
        }
    }

    public function scopeOrderByTerm($query, $term)
    {
        if(($term != '') || ($term != null)) {

            if( $term == 'likes') {
                return $query->orderBy('likes_number', 'desc');
            }

            if( $term == 'views') {
                return $query->orderBy('views_number', 'desc');
            }

            if( $term == 'recent') {
                return $query->orderBy('created_at', 'desc');
            }

        } else {

            return $query->orderBy('created_at', 'desc');

        }
    }

    public function scopeOrderByAppearsHomeAsc($query)
    {
        return $query->orderBy('appears_home', 'asc');
    }

    public function scopeOrderByAppearsHomeDesc($query)
    {
        return $query->orderBy('appears_home', 'desc');
    }

    public function scopeOrderByCreatedAtAsc($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    public function scopeOrderByCreatedAtDesc($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeOrderByUpdatedAtAsc($query)
    {
        return $query->orderBy('updated_at', 'asc');
    }

    public function scopeOrderByUpdatedAtDesc($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
}
