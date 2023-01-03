<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'avatar',
        'date_birth',
        'gender',
        'adult',
        'accept_private_policy',
        'active',
        'token',
        'role_id',
        'newsletter_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'token',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'adult' => 'integer',
        'accept_private_policy' => 'integer',
        'active' => 'integer',
        'role_id' => 'integer',
        'newsletter_id' => 'integer'
    ];

    /*public function setPasswordAttribute($password){
        $this->attributes['password'] = bcrypt($password);
    }*/

    /****************************************************************/
    /************************ RELATIONS *****************************/
    /****************************************************************/
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public function newsletter()
    {
        return $this->belongsTo('App\Models\Newsletter', 'newsletter_id');
    }

    public function entrys()
    {
        return $this->hasMany('App\Models\Entry', 'author_id');
    }

    public function userModulePermission()
    {
        return $this->hasMany('App\Models\UserModulePermission', 'user_id');
    }

    public function userpreferences()
    {
        return $this->hasMany('App\Models\UserPreference', 'user_id');
    }

    public function entrylikes()
    {
        return $this->hasMany('App\Models\EntryLike', 'user_id');
    }

    public function entrychapterusers()
    {
        return $this->hasMany('App\Models\EntryChapterUser', 'user_id');
    }

    public function entrychapterlikes()
    {
        return $this->hasMany('App\Models\EntryChapterLike', 'user_id');
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

    public function scopeByEmail($query, $email)
    {
        if ($email != '') {
            return $query->orWhere('email', 'LIKE', '%' . trim($email) . '%');
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
