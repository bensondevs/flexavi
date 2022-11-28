<?php

namespace App\Models\User;

use App\Enums\Role;
use App\Models\Address\Address;
use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Invitation\RegisterInvitation;
use App\Models\Owner\Owner;
use App\Observers\UserObserver as Observer;
use App\Repositories\Auths\AuthRepository;
use App\Rules\Helpers\Media;
use App\Services\Permission\PermissionService;
use Exception;
use Illuminate\Database\Eloquent\{Builder, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Znck\Eloquent\Traits\BelongsToThrough;

class User extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;
    use HasRoles;
    use SoftDeletes;
    use CausesActivity;
    use Notifiable;
    use BelongsToThrough;

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
    public array $searchableFields = ['fullname', 'phone', 'email'];

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname',
        'birth_date',
        'phone',
        'profile_picture_path',
        'email',
        'registration_code',
        'mollie_customer_id',
        'email_verified_at',
        'last_login_at',
        'phone_verified_at',
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
    * Relationship that will be loaded whenever the model loaded
    *
    * @var array
    */
    protected $with = ['permissions'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
    ];

    /**
     * Set the default image placeholder file
     *
     * @return string
     */
    public static function placeholder(): string
    {
        $placeholderFilename = 'placeholder-user.webp';
        $filename = Media::randomCustomFilename(
            explode('.', $placeholderFilename)[1]
        );
        Storage::copy(
            $placeholderFilename,
            "users/$filename"
        );

        return $filename;
    }

    /**
     * Check if email is used by any user
     *
     * @static
     * @param string $email
     * @return bool
     */
    public static function checkEmailUsed(string $email): bool
    {
        return self::where('email', $email)->exists();
    }

    /**
     * Find user by email, if not found abort it
     *
     * @throw HTTPException
     * @param string $email
     * @return User|null
     */
    public static function findByEmailOrFail(string $email): ?User
    {
        return self::findByEmail($email, true);
    }

    /**
     * Find user by email
     *
     * @static
     * @param string $email
     * @param bool $abortIfNotFound
     * @return User|null
     */
    public static function findByEmail(
        string $email,
        bool   $abortIfNotFound = false
    ): ?User {
        $query = self::where('email', $email);

        return $abortIfNotFound ? $query->firstOrFail() : $query->first();
    }

    /**
     * Find user by social id using driver of social media and id
     *
     * @param string $driver
     * @param string $id
     * @return User|null
     */
    public static function findBySocialId(string $driver, string $id): ?User
    {
        return self::where($driver . '_id', $id)->first();
    }

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        self::observe(Observer::class);
    }

    /**
     * Get user addresses
     *
     * @return HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get user owner model
     *
     * @return HasOne
     */
    public function owner(): HasOne
    {
        return $this->hasOne(Owner::class);
    }

    /**
     * Get user employee model
     *
     * @return HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get company of the user
     *
     * @return Company|null
     */
    public function getCompanyAttribute(): Company|null
    {
        $role = $this->fresh()->roles->first();
        $roleName = strtolower($role->name);
        try {
            return $this->{$roleName}->company;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Alias of register_invitation() method
     *
     * @return BelongsTo
     */
    public function registerInvitation(): BelongsTo
    {
        return $this->register_invitation();
    }

    /**
     * Get register invitation of user
     *
     * @return BelongsTo
     */
    public function register_invitation(): BelongsTo
    {
        $class = RegisterInvitation::class;
        $code = 'registration_code';

        return $this->belongsTo($class, $code, $code);
    }

    /**
     * Reset password token of the user
     *
     * @return HasOne
     */
    public function resetPasswordToken(): HasOne
    {
        return $this->hasOne(PasswordReset::class, 'email', 'email');
    }

    /**
     * Create settable "unhashed_password" attribute.
     * This settable attribute will allow password assignation
     * with encryption before delivered to database
     *
     * @param string $password
     * @return void
     */
    public function setUnhashedPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Perform download action of the image file
     *
     * @return StreamedResponse
     * @throws HttpException
     */
    public function downloadProfilePictureImage(): StreamedResponse
    {
        if (
            Storage::missing(
                "users/$this->profile_picture_path"
            )
        ) {
            abort(404);
        }

        return Storage::download(
            "users/$this->profile_picture_path"
        );
    }

    /**
     * Create settable "profile_picture" attribute.
     * This settable attribute will allow uploading image and record the
     * path where image uploaded to column of "profile_picture_path"
     *
     * @param UploadedFile|null $file
     * @return void
     */
    public function setProfilePictureAttribute(UploadedFile $file = null): void
    {
        if ($file) {
            $filename = Media::randomFilename($file);
            Storage::putFileAs('users', $file, $filename);
            $this->attributes['profile_picture_path'] = $filename;
        }
    }

    /**
     * Create settable "profile_picture_url" attribute.
     * This settable attribute will execute image download from inserted url
     * and save it into the storage. After that, this will record the
     * path to the file into the column of "profile_picture_path"
     *
     * @param string $imageUrl
     * @return void
     */
    public function setProfilePictureUrlAttribute(string $imageUrl): void
    {
        $tmp = explode('/', $imageUrl);
        $filename = $tmp[count($tmp) - 1];
        Storage::put(
            "users/$filename",
            file_get_contents($imageUrl)
        );
        $this->attributes['profile_picture_path'] = $filename;
    }

    /**
     * Create callable "profile_picture_url" attribute.
     * This callable attribute will return url where profile picture
     * can be accessed through browser or as downloadable resource
     *
     * @return string|null
     */
    public function getProfilePictureUrlAttribute(): ?string
    {
        if (
            Storage::missing(
                "users/$this->profile_picture_path"
            )
        ) {
            return null;
        }

        return Storage::url(
            "users/$this->profile_picture_path"
        );
    }

    /**
     * Create callable "role_name" attribute
     * This callable attribute will return the role name of the user
     *
     * @return string
     */

    public function getRoleNameAttribute(): string
    {
        $roleName = $this->getUserRoleAttribute();
        return ucwords($roleName);
    }

    /**
     * Create callable "user_role" attribute
     * This callable attribute will return the role name of the user
     *
     * @return string
     */
    public function getUserRoleAttribute(): string
    {
        $role = $this->roles->first();
        $roleName = is_null($role) ? '' : $role->name;
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
    public function getRoleModelAttribute(): mixed
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
    public function getPhoneVerifiedStatusAttribute(): bool
    {
        $verifiedAt = $this->phone_verified_at;

        return (bool)$verifiedAt;
    }

    /**
     * Create callable "email_verified_status" attribute
     * This callable attribute will return the boolean status
     * of the email verification
     *
     * @return bool
     */
    public function getEmailVerifiedStatusAttribute(): bool
    {
        $verifiedAt = $this->email_verified_at;

        return (bool)$verifiedAt;
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
    public function generateToken(): string
    {
        $token = $this->createToken(time())->plainTextToken;
        $this->setLastLogin();

        return $this->token = $token;
    }

    /**
     * Set the user's last login time
     *
     * @return void
     */
    public function setLastLogin(): void
    {
        $this->last_login_at = now()->toDateTimeString();
        $this->save();
    }

    /**
     * Generate reset password token.
     * This reset password token will be used in user forgot password.
     * This method will return the PasswordReset model
     *
     * @return PasswordReset
     */
    public function generateResetPasswordToken(): PasswordReset
    {
        return $this->resetPasswordToken = PasswordReset::create([
            'email' => $this->attributes['email'],
            'phone' => $this->attributes['phone'],
            'token' => randomToken(),
            'via' => 'email',
            'created_at' => now(),
            'expired_at' => now()->addMinutes(5)
        ]);
    }

    /**
     * Create email verification and send to user
     *
     * @return EmailVerification
     */
    public function createEmailVerification(): EmailVerification
    {
        $verification = new EmailVerification();
        $verification->model = self::class;
        $verification->model_id = $this->attributes['id'];
        $verification->expired_at = carbon()
            ->now()
            ->addDays(3);
        $verification->save();

        return $verification;
    }

    /**
     * Check whether user has company.
     *
     * @return bool
     */
    public function hasCompany(): bool
    {
        $role = $this->fresh()->roles->first();
        $roleName = $role->name;
        if ($roleName === Role::Admin) {
            return false;
        }

        $roleInstance = match ($roleName) {
            Role::Owner => Owner::whereUserId($this->id)->first(),
            Role::Employee => Employee::whereUserId($this->id)->first(),
        };
        if (!$roleInstance) {
            return false;
        }

        return Company::whereId($roleInstance->company_id)->exists();
    }

    /**
     * Check if user has company permission
     *
     * @throw HttpException
     * @param mixed $company
     * @param string $doAction
     * @return bool
     */
    public function hasCompanyPermission(mixed $company, string $doAction = ''): bool
    {
        // Ensure the given instance is company model
        // This will enable the system to prohibit soft-deleted company
        if (!$company instanceof Company) {
            $company = Company::find($company) ?:
                abort(
                    404,
                    'The checked company is not found or soft-deleted.'
                );
        }

        // Ensure current user is belongs to the specified company
        // Administrator wil be allowed directly
        $permissionService = new PermissionService();
        if (!$permissionService->hasAccessInCompany($this, $company)) {
            return false;
        }

        return $this->fresh()->hasDirectPermission($doAction);
    }

    /**
     * Check if user has company permission
     *
     * @throw HttpException
     * @param mixed $company
     * @param string $doAction
     * @param bool $withSubscriptionCheck
     * @return bool
     */
    public function hasCompanyDirectPermission(
        mixed  $company,
        string $doAction = '',
        bool   $withSubscriptionCheck = true
    ): bool {
        // Ensure the given instance is company model
        // This will enable the system to prohibit soft-deleted company
        if (!$company instanceof Company) {
            $company = Company::find($company) ?:
                abort(
                    404,
                    'The checked company is not found or soft-deleted.'
                );
        }

        // Ensure current user is belongs to the specified company
        // Administrator wil be allowed directly
        $permissionService = new PermissionService();
        if (!$permissionService->hasAccessInCompany($this, $company)) {
            return false;
        }

        // After the user is validated as part of the company
        // Now it's time to check whether the specified user has direct permission to do action
        if (!$this->fresh()->hasDirectPermission($doAction)) {
            abort(403, 'You do not have direct permission to ' . $doAction);
        }

        // At this point, when the parameter with subscription check is disabled
        // The user can simply pass the check
        if (!$withSubscriptionCheck) {
            return true;
        }

        return $company->hasActiveSubscription();
    }

    /**
     * Check if user has company permission
     *
     * @param string $doAction
     * @param bool $withSubscriptionCheck
     * @return bool
     */
    public function hasDirectPermissionTwo(
        string $doAction,
        bool   $withSubscriptionCheck = true
    ): bool {
        $role = $this->fresh()->roles->first();
        switch ($role->name) {
            case Role::Admin:
                return true;
            case Role::Owner:
                $owner = $this->owner;
                if ($owner->company) {
                    return $this->fresh()->hasDirectPermission($doAction) && (!$withSubscriptionCheck || $owner->company->hasActiveSubscription());
                }

                return $this->fresh()->hasDirectPermission($doAction);
            case Role::Employee:
                $employee = $this->employee;
                return $this->fresh()->hasDirectPermission($doAction) && (!$withSubscriptionCheck || $employee->company->hasActiveSubscription());
            default:
                abort(
                    500,
                    'Current user has no role, there must be something wrong with role assignment.'
                );
        }
    }

    /**
     * Check password correct is match or not
     *
     * @param string $password
     * @return bool
     */
    public function isPasswordMatch(string $password): bool
    {
        return hash_check($password, $this->attributes['password']);
    }

    /**
     * Send email verification to verify user email
     *
     * @return void
     */
    public function sendEmailVerification(): void
    {
        $authRepository = new AuthRepository();
        $authRepository->setModel($this);
        $authRepository->sendEmailVerification();
    }

    /**
     * Verify the user email.
     *
     * @return bool
     */
    public function verifyEmail(): bool
    {
        $this->attributes['email_verified_at'] = now();

        return $this->save();
    }

    /**
     * Unverified user email
     * Usually used when email is changed
     *
     * @return bool
     */
    public function unverifyEmail(): bool
    {
        $this->attributes['email_verified_at'] = null;

        return $this->save();
    }

    /**
     * Determine if the user is owner
     *
     * @return bool
     */
    public function isOwner(): bool
    {
        return $this->user_role === Role::Owner;
    }

    /**
     * Determine if the user is employee
     *
     * @return bool
     */
    public function isEmployee(): bool
    {
        return $this->user_role === Role::Employee;
    }

    /**
     * Determine if the user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->user_role === Role::Admin;
    }

    /**
     * Determine if the user is customer
     *
     * @return bool
     */
    public function isCustomer(): bool
    {
        return $this->user_role === Role::Customer;
    }

    /**
     * Define the searchable query
     *
     * @param Builder $query
     * @return Builder
     */
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with('owner')->with('employee');
    }
}
