<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\RegisterInvitationObserver;
use App\Enums\RegisterInvitation\RegisterInvitationStatus as Status;

class RegisterInvitation extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;
    use HasEvents;

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
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'invited_email',
        'expiry_time',
        'role',
    ];

    /**
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        'invited_email',
    ];

    /**
     * Set which attribute that should be casted
     * 
     * @var array
     */
    protected $casts = [
        'attachments' => 'array',
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
        self::observe(RegisterInvitationObserver::class);

        self::retrieved(function ($invitation) {
            // Check expired whenever retrieved
            $invitation->checkExpired();
        });

    	self::creating(function ($invitation) {
            $invitation->registration_code = $invitation->registration_code ?:
                randomString(6);
            $invitation->expiry_time = $invitation->expiry_time ?:
                carbon()->now()->addDays(3);
    	});
    }

    /**
     * Invited user from this invitation
     */
    public function invitedUser()
    {
        return $this->hasOne(User::class, 'registration_code', 'registration_code');
    }

    /**
     * Find register invitation by code, 
     * set the second parameter to true to abort 404 if no invitation is found
     * 
     * @param string  $code
     * @param bool  $abortIfFail
     * @return \App\Models\RegisterInvitation
     */
    public static function findByCode(string $code, bool $abortIfFail = false)
    {
        $invitation = self::where('registration_code', $code);

        return $abortIfFail ? 
            $invitation->firstOrFail() : 
            $invitation->first();
    }

    /**
     * Check if registration invitation is expired or not.
     * If found expired, but status if not expired, then update it
     * and save it.
     * 
     * @return bool
     */
    public function checkExpired()
    {
        $status = $this->attributes['status'];
        if ($status == Status::Expired) return true;

        $expiryTime = $this->attributes['expiry_time'];
        if (carbon()->now() >= carbon()->parse($expiryTime)) {
            $this->attributes['status'] = Status::Expired;
            return $this->save();
        }

        return false;
    }

    /**
     * Create settable "attachments" attribute.
     * This settable attribute will allow attachments JSON 
     * using array and convert it as JSON
     * 
     * @param array  $attachments
     * @return void
     */
    public function setAttachmentsAttribute(array $attachments)
    {
        $this->attributes['attachments'] = json_encode($attachments);
    }

    /**
     * Create callable "attachments" attribute
     * This callable attribute will allow getting attachments as array
     * 
     * @return array
     */
    public function getAttachmentsAttribute()
    {
        $rawAttachments = $this->attributes['attachments'];
        $attachments = json_decode($rawAttachments, true);

        if (is_string($attachments)) $attachments = json_decode($attachments, true);

        return $attachments ?: [];
    }

    /**
     * Create callable "role_model" attribute
     * This callable attribute will return blank laravel-model 
     * for new user's laravel-model of role creation
     * 
     * @return mixed
     */
    public function getRoleModelAttribute()
    {
        switch ($this->attributes['role']) {
            case 'owner':
                return new Owner();
                break;
            case 'employee':
                return new Employee();
                break;

            default:
                return new Owner();
                break;
        }
    }

    /**
     * Create callable "role_class_name" attribute
     * This callable attribute will return laravel-model's name
     * 
     * @return string
     */
    public function getRoleClassNameAttribute()
    {
        switch ($this->attributes['role']) {
            case 'owner':
                return Owner::class;
                break;
            case 'employee':
                return Employee::class;
                break;

            default:
                return Owner::class;
                break;
        }
    }

    /**
     * Collect all statuses as array for select-option items
     * 
     * @return array
     */
    public static function collectAllStatuses()
    {
        return Status::asSelectArray();
    }

    /**
     * Set register invitation status as Used
     * 
     * @return bool
     */
    public function setUsed()
    {
        $this->attributes['status'] = Status::Used;
        return $this->save();
    }
}