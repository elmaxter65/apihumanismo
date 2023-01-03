<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'index_content',
        'active',
        'init'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'index_content' => 'integer',
        'active' => 'integer',
        'init' => 'integer'
    ];

    /****************************************************************/
    /************************* SCOPES *******************************/
    /****************************************************************/

    public function scopeByName($query, $name)
    {
        if(($name != '') || ($name != null)) {
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
