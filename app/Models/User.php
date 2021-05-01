<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    use HasRoles;
    use CausesActivity;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Personal Identity
        'fullname',
        'salutation',
        'birth_date',
        'id_card_type',
        'id_card_number',
        'phone',
        'address',
        'profile_picture_url',

        // Authentication
        'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($user) {
            $user->incrementing = false;
            $user->id = Uuid::generate()->string;
        });
    }

    public function owners()
    {
        return $this->hasMany(
            'App\Models\Owner', 
            'user_id', 
            'id'
        );
    }

    public function employees()
    {
        return $this->hasMany(
            'App\Models\Employee', 
            'user_id', 
            'id'
        );
    }

    public function setUnhashedPasswordAttribute(string $value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function hasCompanyPermission($companyId)
    {
        //

        return true;
    }
}
