<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;

class RegisterInvitation extends Model
{
    protected $table = 'register_invitations';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'invited_email',
        'expiry_time',
    ];

    protected $hidden = [
        
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    protected static function boot()
    {
    	parent::boot();

        self::retrieved(function ($invitation) {
            // Check expired whenever retrieved
            $invitation->checkExpired();
        });

    	self::creating(function ($invitation) {
            $invitation->registration_code = $invitation->registration_code ?
                $invitation->registration_code :
                randomString(6);
            $invitation->expiry_time = $invitation->expiry_time ?:
                carbon()->now()->addDays(3);
    	});
    }

    public static function findByCode($code)
    {
        return self::where('registration_code', $code)->first();
    }

    public function checkExpired()
    {
        $status = $this->attributes['status'];
        if ($status == RegisterInvitationStatus::Expired) return true;

        $expiryTime = $this->attributes['expiry_time'];
        if (carbon()->now() >= carbon()->parse($expiryTime)) {
            $this->attributes['status'] = RegisterInvitationStatus::Expired;
            return $this->save();
        }

        return false;
    }

    public function setAttachmentsAttribute(array $attachments)
    {
        $this->attributes['attachments'] = json_encode($attachments);
    }

    public function getAttachmentsAttribute()
    {
        $rawAttachments = $this->attributes['attachments'];
        $attachments = json_decode($rawAttachments, true);

        if (! $attachments) return [];

        $registrationCode = $this->attributes['registration_code'];
        $attachments['registration_code'] = $registrationCode;

        return $attachments;
    }

    public static function collectAllStatuses()
    {
        return RegisterInvitationStatus::asSelectArray();
    }
}