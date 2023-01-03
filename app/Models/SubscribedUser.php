<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscribedUser extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'newsletter_id',
        'language_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'newsletter_id' => 'integer',
        'language_id' => 'integer'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/

    public function newsletter()
    {
        return $this->belongsTo('App\Models\Newsletter', 'newsletter_id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }
    /****************************************************************/
    /************************* SCOPES *******************************/
    /****************************************************************/

    public function scopeByLanguage($query, $language_id)
    {
        if (($language_id != '') || ($language_id != null)) {
            return $query->whereHas('language', function($q2) use($language_id){
                return $q2->where('id', '=', $language_id);
            });
        }
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
