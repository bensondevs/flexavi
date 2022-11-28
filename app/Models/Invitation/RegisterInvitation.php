<?php

namespace App\Models\Invitation;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;
use App\Enums\RegisterInvitation\RegisterInvitationStatus as Status;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeInvitation;
use App\Models\Owner\Owner;
use App\Models\User\User;
use App\Observers\RegisterInvitationObserver;
use Illuminate\Database\Eloquent\{Model, Relations\MorphTo, SoftDeletes};
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;


class RegisterInvitation extends Model
{

    use HasFactory;

    use SoftDeletes;
    use HasEvents;

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
    public $incrementing = true;

    /**
     * Set which columns are searchable
     *
     * @var array
     */
    public array $searchableFields = ['invited_email'];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'register_invitations';

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
    protected $fillable = ['invited_email', 'expiry_time', 'role', 'invitationable_id', 'invitationable_type', 'registration_code'];

    /**
     * Set which attribute that should be casted
     *
     * @var array
     */
    protected $casts = [
        'attachments' => 'array',
    ];

    /**
     * Find register invitation by code,
     * set the second parameter to true to abort 404 if no invitation is found
     *
     * @param string $code
     * @param bool $abortIfFail
     * @return RegisterInvitation|null
     */
    public static function findByCode(string $code, bool $abortIfFail = false): ?RegisterInvitation
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
        return Status::asSelectArray();
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
        self::observe(RegisterInvitationObserver::class);
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
        $status = $this->attributes['status'];
        if ($status == Status::Expired) {
            return true;
        }
        $expiryTime = $this->attributes['expiry_time'];
        if (carbon()->now() >= carbon()->parse($expiryTime)) {
            $this->attributes['status'] = Status::Expired;
            return $this->save();
        }

        return false;
    }

    /**
     * Check if registration available to usage
     *
     * @return bool
     */
    public function isUsableNow(): bool
    {
        return (now()->isBefore($this->attributes['expiry_time']) && $this->isActive());
    }

    /**
     * Check if registration invitation is expired or not.
     * If found expired, but status if not expired, then update it
     * and save it.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->attributes['status'] === RegisterInvitationStatus::Active;
    }


    /**
     * Invited user from this invitation
     *
     * @return HasOne
     */
    public function invitedUser(): HasOne
    {
        return $this->hasOne(
            User::class,
            'registration_code',
            'registration_code'
        );
    }

    /**
     * Create settable "attachments" attribute.
     * This settable attribute will allow attachments JSON
     * using array and convert it as JSON
     *
     * @param array $attachments
     * @return void
     */
    public function setAttachmentsAttribute(array $attachments): void
    {
        $this->attributes['attachments'] = json_encode($attachments);
    }

    /**
     * Create callable "attachments" attribute
     * This callable attribute will allow getting attachments as array
     *
     * @return array
     */
    public function getAttachmentsAttribute(): array
    {
        $rawAttachments = $this->attributes['attachments'];
        $attachments = json_decode($rawAttachments, true);
        if (is_string($attachments)) {
            $attachments = json_decode($attachments, true);
        }

        return $attachments ?: [];
    }

    /**
     * Create callable "role_model" attribute
     * This callable attribute will return blank laravel-model
     * for new user's laravel-model of role creation
     *
     * @return Owner|Employee
     */
    public function getRoleModelAttribute(): Owner|Employee
    {
        return match (get_class($this->invitationable)) {
            EmployeeInvitation::class => new Employee(),
            default => new Owner(),
        };
    }

    /**
     * Create callable "role" attribute
     * This callable attribute will return blank laravel-model
     * for new user's laravel-model of role creation
     *
     * @return string
     */
    public function getRoleAttribute(): string
    {
        return match (get_class($this->invitationable)) {
            EmployeeInvitation::class => 'employee',
            default => 'owner',
        };
    }

    /**
     * Create callable "role_class_name" attribute
     * This callable attribute will return laravel-model's name
     *
     * @return string
     */
    public function getRoleClassNameAttribute(): string
    {
        return match (get_class($this->invitationable)) {
            EmployeeInvitation::class => Employee::class,
            default => Owner::class,
        };
    }

    /**
     * Set register invitation status as Used
     *
     * @return bool
     */
    public function setUsed(): bool
    {
        $this->attributes['status'] = Status::Used;

        return $this->save();
    }

    /**
     * Get invitationable
     *
     * @return MorphTo
     */
    public function invitationable(): MorphTo
    {
        return $this->morphTo();
    }
}
