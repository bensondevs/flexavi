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
use App\Repositories\Auths\AuthRepository;
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

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key of the model
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamp recording
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use increment or not
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set which columns are searchable
     * 
     * @var array
     */
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

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(UserObserver::class);

        self::creating(function ($user) {
            $user->incrementing = false;
            $user->id = ($user->id) ?: Uuid::generate()->string;
        });
    }

    /**
     * Create callable "id_card_type_description" attribute
     * This callable attribute return the enum description of
     * ID Card Type
     * 
     * @return string
     */
    public function getIdCardTypeDescriptionAttribute()
    {
        $type = $this->attributes['id_card_type'];
        return UserIdCardType::getDescription($type);
    }

    /**
     * Get user addresses
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get user owner model
     */
    public function owner()
    {
        return $this->hasOne(Owner::class);
    }

    /**
     * Get user employee model
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get company of the user
     * 
     * @return \App\Models\Company
     */
    public function getCompanyAttribute()
    {
        switch (true) {
            case $this->hasRole('owner'):
                $role = $this->owner;
                break;
            case $this->hasRole('employee'):
                $role = $this->employee;
                break;
            
            default:
                $role = $this->owner;
                break;
        }

        return $this->company = $role->company;
    }

    /**
     * Get register invitation of user
     */
    public function register_invitation()
    {
        $class = RegisterInvitation::class;
        $code = 'registration_code';
        return $this->belongsTo($class, $code, $code);
    }

    /**
     * Alias of register_invitation() method
     */
    public function registerInvitation()
    {
        return $this->register_invitation();
    }

    /**
     * Reset password token of the user
     */
    public function resetPasswordToken()
    {
        return $this->hasOne(PasswordReset::class, 'email', 'email');
    }

    /**
     * Create settable "unhashed_password" attribute.
     * This settable attribute will allow password assignation
     * with encryption before delivered to database
     * 
     * @param string  $password
     * @return void
     */
    public function setUnhashedPasswordAttribute(string $password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Create settable "profile_picture" attribute.
     * This settable attribute will allow uploading image and record the
     * path where image uploaded to column of "profile_picture_path"
     * 
     * @param mixed  $imageFile
     * @return void
     */
    public function setProfilePictureAttribute($imageFile)
    {
        $path = 'uploads/users/profile_pictures/';
        $image = uploadFile($imageFile, $path);

        $this->attributes['profile_picture_path'] = $image->path;
    }

    /**
     * Create callable "profile_picture_url" attribute.
     * This callable attribute will return url where profile picture
     * can be accessed through browser or as downloadable resource
     * 
     * @return string
     */
    public function getProfilePictureUrlAttribute()
    {
        $path = $this->attributes['profile_picture_path'];
        return Storage::url($path);
    }

    /**
     * Create settable "profile_pictire_url" attribute.
     * This settable attribute will execute image download from inserted url
     * and save it into the storage. After that, this will record the
     * path to the file into the column of "profile_picture_path"
     * 
     * @param string  $pictureUrl
     * @return void
     */
    public function setProfilePictureUrlAttribute(string $pictureUrl)
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

    /**
     * Create callable "user_role" attribute
     * This callable attribute will return the role name of the user
     * 
     * @return string
     */
    public function getUserRoleAttribute()
    {
        $role = $this->roles->first();
        $roleName = $role->name;
        unset($this->roles);
        return $roleName;
    }

    /**
     * Create callable "role_model" attribute
     * This callable attribute will return the laravel role model
     * attached to user
     * 
     * @return mixed
     */
    public function getRoleModelAttribute()
    {
        $roleName = $this->getUserRoleAttribute() ?: 'owner';
        return $this->{$roleName};
    }

    /**
     * Create callable "phone_verified_status" attribute
     * This callable attribute will return the boolean status
     * of the phone verification
     * 
     * @return bool
     */
    public function getPhoneVerifiedStatusAttribute()
    {
        $verifiedAt = $this->attributes['phone_verified_at'];
        return (bool) $verifiedAt;
    }

    /**
     * Create callable "email_verified_status" attribute
     * This callable attribute will return the boolean status
     * of the email verification
     * 
     * @return bool
     */
    public function getEmailVerifiedStatusAttribute()
    {
        $verifiedAt = $this->attributes['email_verified_at'];
        return (bool) $verifiedAt;
    }

    /**
     * Generate authentication token API for user
     * The generated token will be used in request header of
     * API request in all authenticated routes.
     * 
     * If the user has active token already, then this method
     * will destroy old token and give new token.
     *  
     * @return string
     */
    public function generateToken()
    {
        // Delete old tokens
        $this->tokens()->delete();

        // Create Token
        $token = $this->createToken(time())->plainTextToken;
        return $this->attributes['token'] = $token;
    }

    /**
     * Generate reset password token.
     * This reset password token will be used in user forgot password.
     * This method will return the PasswordReset model
     * 
     * @return \App\Models\PasswordReset
     */
    public function generateResetPasswordToken()
    {
        return $this->resetPasswordToken = PasswordReset::create([
            'email' => $this->attributes['email'],
            'token' => random_string(rand(10, 15)),
            'created_at' => now(),
        ]);
    }

    /**
     * Create email verification and send to user
     * 
     * @return \App\Models\EmailVerification
     */
    public function createEmailVerification()
    {
        $verification = new EmailVerification();
        $verification->model = self::class;
        $verification->model_id = $this->attributes['id'];
        $verification->expired_at = carbon()->now()->addDays(3);
        $verification->save();

        return $verification;
    }

    /**
     * Check if user has company permission
     * 
     * @param string  $companyId
     * @param string|null  $doAction
     * @return bool
     */
    public function hasCompanyPermission($companyId, string $doAction = '')
    {
        $role = $this->roles->first();

        // Allow Administrators
        if ($role->name == 'admin') return true;

        // Allow Owner
        if ($role->name == 'owner') {
            $owner = $this->owner;
            return ($owner->company_id === $companyId);
        }

        // Allow Employee
        if ($role->name == 'employee') {
            $employee = $this->employee;
            return ($employee->company_id == $companyId) && $employee->hasPermissionTo($doAction);
        }

        // Disallow, because pass none
        return false;
    }

    /**
     * Collect all id card types as array
     * 
     * @static
     * @return array
     */
    public static function collectAllIdCardTypes()
    {
        return UserIdCardType::asSelectArray();
    }

    /**
     * Check if email is used by any user
     * 
     * @static
     * @param string  $email
     * @return bool
     */
    public static function checkEmailUsed(string $email)
    {
        return self::where('email', $email)->exists();
    }

    /**
     * Find user by email
     * 
     * @static
     * @param string  $email
     * @param bool  $abortIfNotFound
     * @return \App\Models\User|null
     */
    public static function findByEmail(string $email, bool $abortIfNotFound = false)
    {
        $query = self::where('email', $email);
        return $abortIfNotFound ?
            $query->firstOrFail() :
            $query->first();
    }

    /**
     * Find user by email, if not found abort it
     * 
     * @param string  $email
     * @return  \App\Models\User|abort(404)
     */
    public static function findByEmailOrFail(string $email)
    {
        return self::findByEmail($email, true);
    }

    /**
     * Find user by social id using driver of social media and id
     * 
     * @param string  $driver
     * @param string  $id
     * @return \App\Models\User|null
     */
    public static function findBySocialId(string $driver, string $id)
    {
        return self::where($driver . '_id', $id)->first();
    }

    /**
     * Check password correct is match or not
     * 
     * @param string  $password
     * @return bool
     */
    public function isPasswordMatch(string $password)
    {
        return hash_check($password, $this->attributes['password']);
    }

    /**
     * Send email verification to verify user email
     * 
     * @return void
     */
    public function sendEmailVerification()
    {
        $authRepository = new AuthRepository();
        $authRepository->setModel($this);
        $authRepository->sendEmailVerification();
    }

    /**
     * Unverify user email
     * Usually used when email is changed
     * 
     * @return bool
     */
    public function unverifyEmail()
    {
        $this->attributes['email_verified_at'] = null;
        return $this->save();
    }

    /**
     * Log activity done by user
     * 
     * @param string  $message
     * @param mixed|null  $model
     * @param array|null  $extra
     * @return void
     */
    public function recordActivity(string $message, $model = null, $extras = [])
    {
        $activity = activity();

        if ($model) $activity = $activity->performedOn($model);
        if ($extras) $activity = $activity->withProperties($extras);

        $activity->log($message);
    }
}
