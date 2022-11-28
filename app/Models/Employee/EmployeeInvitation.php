<?php

namespace App\Models\Employee;

use App\Enums\Employee\EmployeeType;
use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Models\Company\Company;
use App\Models\Invitation\RegisterInvitation;
use App\Models\User\User;
use App\Observers\EmployeeInvitationObserver as Observer;
use App\Rules\Helpers\Media;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, Relations\MorphOne, SoftDeletes};
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmployeeInvitation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasEvents;
    use Searchable;

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
    public array $searchableFields = [
        'invited_email',
        'name',
        'registration_code',
        'name',
        'phone',
        'birth_date',

        'title',

        'status',
        'sent_at',
        'used_at',
        'cancelled_at',
        'marked_expired_at',

        'permissions',
    ];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'employee_invitations';

    /**
     * The primary key of the model
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'invited_email',
        'registration_code',
        'company_id',
        'name',
        'phone',
        'birth_date',
        'role',
        'status',
        'contract_file_path',
        'expiry_time',
        'title',

        'status',
        'sent_at',
        'used_at',
        'cancelled_at',
        'marked_expired_at',

        'permissions',
    ];

    protected $casts = [
        'role' => 'integer',
        'permissions' => 'array',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();
        self::observe(Observer::class);
        self::retrieved(function ($invitation) {
            $invitation->checkExpired();
        });
    }

    /**
     * Check if registration invitation is expired or not.
     * If found expired, but status if not expired, then update it
     * and save it.
     *
     * @return bool
     */
    public function checkExpired(): bool
    {
        if ($this->isExpired()) {
            return true;
        }

        $expiryTime = $this->attributes['expiry_time'];
        if (carbon()->now() >= carbon()->parse($expiryTime)) {
            return $this->expired();
        }

        return false;
    }

    /**
     * Check whether the employee invitation is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->status === EmployeeInvitationStatus::Expired;
    }

    /**
     * Find register invitation by code,
     * set the second parameter to true to abort 404 if no invitation is found
     *
     * @param string $code
     * @param bool $abortIfFail
     * @return EmployeeInvitation|null
     */
    public static function findByCode(string $code, bool $abortIfFail = false): ?EmployeeInvitation
    {
        $invitation = self::where('registration_code', $code);

        return $abortIfFail ? $invitation->firstOrFail() : $invitation->first();
    }

    /**
     * Collect all statuses as array for select-option items
     *
     * @return array
     */
    public static function collectAllStatuses(): array
    {
        return EmployeeInvitationStatus::asSelectArray();
    }

    /**
     * Get the company of the invitation
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get invited user
     *
     * @return BelongsTo
     */
    public function invitedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registration_code', 'registration_code');
    }

    /**
     * Create callable "role_model" attribute
     * This callable attribute will return blank laravel-model
     * for new user's laravel-model of role creation
     *
     * @return Employee
     */
    public function getRoleModelAttribute(): Employee
    {
        return new Employee();
    }

    /**
     * Create callable "role_class_name" attribute
     * This callable attribute will return laravel-model's name
     *
     * @return string
     */
    public function getRoleClassNameAttribute(): string
    {
        return Employee::class;
    }

    /**
     * Set register invitation status as Used
     *
     * @return bool
     */
    public function setUsed(): bool
    {
        $this->attributes['status'] = EmployeeInvitationStatus::Used;

        return $this->save();
    }

    /**
     * Perform download action of the contract file
     *
     * @return StreamedResponse
     * @throws HttpException
     */
    public function downloadContractFile(): StreamedResponse
    {
        if (
            Storage::missing(
                "employees/invitations/$this->contract_file_path"
            )
        ) {
            abort(404);
        }

        return Storage::download(
            "employees/invitations/$this->contract_file_path"
        );
    }

    /**
     * Create settable attribute of "contract_file"
     * This settable attribute will set the "contract_file_path" and upload
     * the file to the storage
     *
     * @param UploadedFile|null $file
     * @return void
     */
    public function setContractFileAttribute(UploadedFile $file = null): void
    {
        if (!is_null($file)) {
            $filename = Media::randomFilename($file);
            Storage::putFileAs(
                'employees/invitations',
                $file,
                $filename
            );
            $this->attributes['contract_file_path'] = $filename;
        }
    }

    /**
     * Create callable attribute of "contract_file_url"
     * This callable attribute will generate url from the file path
     *
     * @return string|null
     */
    public function getContractFileUrlAttribute(): ?string
    {
        if (
            Storage::missing(
                "employees/invitations/$this->contract_file_path"
            )
        ) {
            return null;
        }

        return Storage::url(
            "employees/invitations/$this->contract_file_path"
        );
    }

    /**
     * Create callable attribute of "role_description"
     * This callable attribute will get the description of role
     *
     * @return string
     */
    public function getRoleDescriptionAttribute(): string
    {
        $role = $this->attributes['role'];

        return EmployeeType::getDescription((int)$role);
    }

     /**
     * Create callable attribute of "status_description"
     * This callable attribute will get the description of status
     *
     * @return ?string
     */
    public function getStatusDescriptionAttribute(): ?string
    {
        $status = $this->attributes['status'] ?? null;

        return is_null($status) ? null : EmployeeInvitationStatus::getDescription((int)$status);
    }

    /**
     * Create callable attribute of "permissions"
     * This callable attribute will get permissions as array.
     *
     * @return array
     */
    public function getPermissionsAttribute(): array
    {
        $permissions = json_decode(
            $this->attributes['permissions'],
            true,
        );

        if (!is_array($permissions)) {
            $permissions = json_decode($permissions, true);
        }

        return $permissions;
    }

    /**
     * Create settable attribute of "permissions"
     * This settable attribute will set permissions as json string.
     *
     * @param array $permissions
     * @return void
     */
    public function setPermissionsAttribute(mixed $permissions): void
    {
        $this->attributes['permissions'] = is_array($permissions) ?
            json_encode($permissions) :
            $permissions;
    }

    /**
     * Get register invitation
     *
     * @return MorphOne
     */
    public function registerInvitation(): MorphOne
    {
        return $this->morphOne(RegisterInvitation::class, 'invitationable');
    }

    /**
     * Check whether the employee invitation is cancelled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->status === EmployeeInvitationStatus::Cancelled;
    }

    /**
     * Set the status of the employee invitation as cancelled.
     *
     * @return bool
     */
    public function cancel(): bool
    {
        $this->status = EmployeeInvitationStatus::Cancelled;
        $this->cancelled_at = now()->toDateTimeString();

        return $this->save();
    }

    /**
     * Set the employee invitation status to expired.
     *
     * @return bool
     */
    public function expire(): bool
    {
        $this->status = EmployeeInvitationStatus::Expired;
        $this->mark_expired_at = now()->toDateTimeString();

        return $this->save();
    }

    /**
     * Mark invitation as sent.
     *
     * @return bool
     */
    public function sent(): bool
    {
        $this->status = EmployeeInvitationStatus::Active;
        $this->sent_at = now()->toDateTimeString();

        return $this->save();
    }

    /**
     * Set the employee invitation status as used.
     *
     * @return bool
     */
    public function used(): bool
    {
        $this->status = EmployeeInvitationStatus::Used;
        $this->used_at = now()->toDateTimeString();

        return $this->save();
    }

    /**
     * Check whether current invitation status is used.
     *
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->status === EmployeeInvitationStatus::Used;
    }
}
