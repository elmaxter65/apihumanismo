<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
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
    public function themes()
    {
        return $this->hasMany('App\Models\ThemeSection', 'section_id');
    }

    /****************************************************************/
    /************************* SCOPES *******************************/
    /****************************************************************/

    public function scopeByName($query, $name)
    {
        if ($name != '') {
            return $query->where('name', 'LIKE', '%' . trim($name) . '%');
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
