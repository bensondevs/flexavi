<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class EmailVerification extends Model
{
    protected $table = 'email_verifications';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    const VERIFICATION_STATUSES = [
        [
            'label' => 'Waiting Verification',
            'value' => 'waiting',
        ],
        [
            'label' => 'Verified',
            'value' => 'verified',
        ],
        [
            'label' => 'Expired',
            'value' => 'expired',
        ],
        [
            'label' => 'Failed',
            'value' => 'failed',
        ]
    ];

    protected $fillable = [
        'model',
        'model_id',
        'model_verification_column',

        'verification_status',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($emailVerification) {
            $emailVerification->id = Uuid::generate()->string;
    	});
    }
}