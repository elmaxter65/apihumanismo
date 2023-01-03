<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function taglanguage()
    {
        return $this->hasMany('App\Models\TagLanguage', 'tag_id');
    }

    public function entrytag()
    {
        return $this->hasMany('App\Models\EntryTag', 'tag_id');
    }

    /****************************************************************/
    /************************* SCOPES *******************************/
    /****************************************************************/

    public function scopeByName($query, $name)
    {
        if ($name != '') {
            return $query->whereHas('taglanguage', function($q2) use($name){
                return $q2->where('name', 'LIKE', '%' . trim($name) . '%');
            });
        }
    }

    public function scopeByLanguage($query, $language_id)
    {
        if(($language_id != '') || ($language_id != null)) {
            return $query->whereHas('taglanguage', function($q2) use($language_id){
                return $q2->where('language_id', '=' , $language_id);
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
