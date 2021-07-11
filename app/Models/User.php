<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use \Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use Webpatser\Uuid\Uuid;

use App\Models\EmailVerification;

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
        'created_at',
        'updated_at',
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
            $user->id = ($user->id) ?: Uuid::generate()->string;
        });
    }

    public function owner()
    {
        return $this->hasOne(
            'App\Models\Owner', 
            'user_id',
            'id'
        );
    }

    public function employee()
    {
        return $this->hasOne(
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
        $path = 'uploads/users/profile_pictures/';
        $image = uploadFile($imageFile, $path);

        $this->attributes['profile_picture_path'] = $image->path;
    }

    public function getProfilePictureUrlAttribute()
    {
        return Storage::url($this->attributes['profile_picture_path']);
    }

    public function getUserRoleAttribute()
    {
        $roleName = $this->roles->first()->name;
        unset($this->roles);
        return $roleName;
    }

    public function generateToken()
    {
        // Delete old tokens
        $this->tokens()->delete();

        // Create Token
        $plainTextToken = $this->createToken(time())->plainTextToken;
        return $this->token = $plainTextToken;
    }

    public function createEmailVerification()
    {
        $verification = new EmailVerification();
        $verification->model = self::class;
        $verification->model_id = $this->attributes['id'];
        $verification->expiry_time = carbon()->now()->addDays(3);
        $verification->save();

        return $verification;
    }

    public function hasCompanyPermission($companyId, string $doAction = '')
    {
        $role = $this->roles->first();

        // Allow Administrators
        if ($role->name == 'admin') return true;

        // Allow Owner
        if ($role->name == 'owner') {
            $owner = $this->owner;
            $company = $owner->company;

            return ($company->id === $companyId);
        }

        // Allow Employee
        if ($role->name == 'employee') {
            $employee = $this->employee;
            if ($employee->company_id == $companyId)
                if ($employee->hasPermissionTo($doAction ?: 'any action'))
                    return true;
        }

        // Disallow, because pass none
        return false;
    }
}
