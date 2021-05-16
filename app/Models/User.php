<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable
{
    use SoftDeletes;
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

    public function setProfilePictureAttribute($imageFile)
    {
        // Upload Profile Picture
        $path = 'storage/uploads/profile_pictures/';
        $uploadedImageName = uploadFile($imageFile, $path);
        $imageUrl = asset($uploadedImageName);

        $this->attributes['profile_picture_url'] = $imageUrl;
    }

    public function getProfilePictureAttribute()
    {
        return $this->attributes['profile_picture_url'];
    }

    public function ownedCompanies()
    {
        return $this->hasManyThrough(
            Company::class, 
            Owner::class
        );
    }

    public function hasCompanyPermission($companyId, string $doAction = '')
    {
        // Allow Administrators
        if ($this->hasRole('admin')) return true;

        // Allow Owner
        if ($this->hasRole('owner')) {
            $owners = $this->owners;
            $company = Company::findOrFail($companyId);
            foreach ($owners as $key => $owner)
                if ($owner->id === $company->owner_id)
                    return true;
        }

        // Allow Employee
        if ($this->hasRole('employee')) {
            $employees = $this->employees;
            foreach ($employees as $key => $employee)
                if ($employee->company_id == $companyId)
                    if ($employee->hasPermissionTo($doAction))
                        return true;
        }

        // Disallow, because pass none
        return false;
    }
}
