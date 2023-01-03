<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Theme extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'indexed',
        'visibility',
        'start_position',
    ];

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function sections()
    {
        return $this->hasMany('App\Models\ThemeSection', 'theme_id');
    }

    public function entries()
    {
        return $this->hasMany('App\Models\Entry', 'theme_id');
    }

    public function userpreferences()
    {
        return $this->hasMany('App\Models\UserPreference', 'preference_id');
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

    /*public function scopeBySection($query, $section_id)
    {
        if(($section_id != '') || ($section_id != null)) {
            return $query->whereHas('sections', function($q2) use($section_id){
                return $q2->where('section_id', '=' , $section_id);
            });
        }
    }*/

	public function scopeBySection($query, $section_id)
	{
        if(($section_id != '') || ($section_id != null)) {
			return $query->whereHas('sections', function($q) use($section_id){
				return $q->whereHas('section', function($q2) use($section_id){
					$q2->where('id', '=' , $section_id);
				});
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
