<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name'
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function entrylanguage()
    {
        return $this->hasMany('App\Models\EntryLanguage', 'language_id');
    }

    public function entrytag()
    {
        return $this->hasMany('App\Models\EntryTag', 'language_id');
    }

    public function taglanguage()
    {
        return $this->hasMany('App\Models\TagLanguage', 'language_id');
    }

    public function subscribeuser()
    {
        return $this->hasMany('App\Models\SubscribedUser', 'language_id');
    }

    /****************************************************************/
    /************************* SCOPES *******************************/
    /****************************************************************/

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
