<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Znck\Eloquent\Traits\BelongsToThrough;

use App\Models\EmailVerification;

use App\Repositories\AuthRepository;

use App\Observers\UserObserver;

use App\Enums\User\UserIdCardType;

class User extends Authenticatable
{
    use Searchable;
    use SoftDeletes;
    use HasFactory, Notifiable, HasApiTokens;
    use HasRoles;
    use CausesActivity;
    use Notifiable;
    use BelongsToThrough;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'fullname',
        'id_card_number',
        'phone',
        'email',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Personal Identity
        'fullname',
        'birth_date',
        'id_card_type',
        'id_card_number',
        'phone',

        // Photo
        'profile_picture_path',

        // Authentication
        'email',

        // Registration Code
        'registration_code',
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
        self::observe(UserObserver::class);

        self::creating(function ($user) {
            $user->incrementing = false;
            $user->id = ($user->id) ?: Uuid::generate()->string;
        });
    }

    public function getIdCardTypeDescriptionAttribute()
    {
        $type = $this->attributes['id_card_type'];
        return UserIdCardType::getDescription($type);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function owner()
    {
        return $this->hasOne(Owner::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function getCompanyAttribute()
    {
        if ($this->hasRole('owner')) {
            $role = $this->owner;
        } else if ($this->hasRole('employee')) {
            $role = $this->employee;
        }

        return $role->company;
    }

    public function register_invitation()
    {
        return $this->belongsTo(RegisterInvitation::class, 'registration_code', 'registration_code');
    }

    public function registerInvitation()
    {
        return $this->register_invitation();
    }

    public function resetPasswordToken()
    {
        return $this->hasOne(PasswordReset::class, 'email', 'email');
    }

    public static function findByEmail(string $email)
    {
        return self::where('email', $email)->first();
    }

    public static function findByEmailOrFail(string $email)
    {
        return self::where('email', $email)->firstOrFail();
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

    public function setProfilePictureUrlAttribute($pictureUrl)
    {
        // Prepare content and name
        $tmp = explode('/', $pictureUrl);
        $pictureName = $tmp[count($tmp) - 1];
        $pictureContent = file_get_contents($pictureUrl);

        // Upload the downloaded image content to folder
        $folderPath = 'uploads/users/profile_pictures/';
        $picturePath = $folderPath . $pictureName;
        $putFile = Storage::put($picturePath, $pictureContent);

        // Set path to the attribute to database
        $this->attributes['profile_picture_path'] = $picturePath;
    }

    public function getUserRoleAttribute()
    {
        $role = $this->roles->first();
        $roleName = $role->name;
        unset($this->roles);
        return $roleName;
    }

    public function getRoleModelAttribute()
    {
        $roleName = $this->getUserRoleAttribute() ?: 'owner';
        return $this->{$roleName};
    }

    public function getPhoneVerifiedStatusAttribute()
    {
        $verifiedAt = $this->attributes['phone_verified_at'];
        return (bool) $verifiedAt;
    }

    public function getEmailVerifiedStatusAttribute()
    {
        $verifiedAt = $this->attributes['email_verified_at'];
        return (bool) $verifiedAt;
    }

    public function generateToken()
    {
        // Delete old tokens
        $this->tokens()->delete();

        // Create Token
        $plainTextToken = $this->createToken(time())->plainTextToken;
        return $this->token = $plainTextToken;
    }

    public function generateResetPasswordToken()
    {
        return $this->resetPasswordToken = PasswordReset::create([
            'email' => $this->attributes['email'],
            'token' => random_string(rand(10, 15)),
            'created_at' => now(),
        ]);
    }

    public function createEmailVerification()
    {
        $verification = new EmailVerification();
        $verification->model = self::class;
        $verification->model_id = $this->attributes['id'];
        $verification->expired_at = carbon()->now()->addDays(3);
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
            if ($employee->company_id == $companyId) {
                if ($employee->hasPermissionTo($doAction ?: 'any action')) {
                    return true;
                }
            }
        }

        // Disallow, because pass none
        return false;
    }

    public static function collectAllIdCardTypes()
    {
        return UserIdCardType::asSelectArray();
    }

    public static function findBySocialId(string $driver, string $id)
    {
        return self::where($driver . '_id', $id)->first();
    }

    public static function checkEmailUsed($email)
    {
        return self::where('email', $email)->count() > 0;
    }

    public function sendEmailVerification()
    {
        $authRepository = new AuthRepository();
        $authRepository->setModel($this);
        $authRepository->sendEmailVerification();
    }

    public function unverifyEmail()
    {
        $this->attributes['email_verified_at'] = null;
        return $this->save();
    }
}
