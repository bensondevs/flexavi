<?php

namespace App\Models\Owner;

use App\Enums\OwnerInvitation\OwnerInvitationStatus;
use App\Models\Company\Company;
use App\Models\Invitation\RegisterInvitation;
use App\Models\Permission\Permission;
use App\Models\User\User;
use App\Observers\OwnerInvitationObserver as Observer;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OwnerInvitation extends Model
{
    use HasFactory;
    use SoftDeletes;
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
    ];
    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'owner_invitations';

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
        'expiry_time',
        'status',
        'permissions'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
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
        $status = $this->attributes['status'];
        if ($status == OwnerInvitationStatus::Expired) {
            return true;
        }
        $expiryTime = $this->attributes['expiry_time'];
        if (carbon()->now() >= carbon()->parse($expiryTime)) {
            $this->attributes['status'] = OwnerInvitationStatus::Expired;
            return $this->save();
        }

        return false;
    }

    /**
     * Find register invitation by code,
     * set the second parameter to true to abort 404 if no invitation is found
     *
     * @param string $code
     * @param bool $abortIfFail
     * @return OwnerInvitation
     */
    public static function findByCode(string $code, bool $abortIfFail = false): OwnerInvitation
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
        return OwnerInvitationStatus::asSelectArray();
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
     * Create callable "role_model" attribute
     * This callable attribute will return blank laravel-model
     * for new user's laravel-model of role creation
     *
     * @return Owner
     */
    public function getRoleModelAttribute(): Owner
    {
        return new Owner();
    }

    /**
     * Create callable "role_class_name" attribute
     * This callable attribute will return laravel-model's name
     *
     * @return string
     */
    public function getRoleClassNameAttribute(): string
    {
        return Owner::class;
    }

    /**
     * Set register invitation status as Used
     *
     * @return bool
     */
    public function setUsed(): bool
    {
        $this->attributes['status'] = OwnerInvitationStatus::Used;

        return $this->save();
    }

    /**
     * Create callable "status_description" attribute
     *
     * @return string
     */
    public function getStatusDescriptionAttribute(): string
    {
        return OwnerInvitationStatus::getDescription($this->attributes['status']);
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
     * Check if registration invitation is used or not.
     * If found used, but status if not used, then update it
     * and save it.
     *
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->attributes['status'] === OwnerInvitationStatus::Used;
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
}
