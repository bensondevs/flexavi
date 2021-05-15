<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

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

    	self::creating(function ($invitation) {
            $invitation->registration_code = $invitation->registration_code ?
                $invitation->registration_code :
                randomString(6);
    	});
    }

    public static function findByCode($code)
    {
        return self::where('registration_code', $code)->first();;
    }

    public function setAttachmentsAttribute(array $attachments)
    {
        $this->attributes['attachments'] = json_encode($attachments);
    }

    public function getAttachmentsAttribute()
    {
        $rawAttachments = $this->attributes['attachments'];
        $attachments = json_decode($rawAttachments);

        return $attachments ?: [];
    }
}