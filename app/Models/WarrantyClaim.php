<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class WarrantyClaim extends Model
{
    protected $table = 'warranty_claims';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'warranty_id',
        'claim_reason',
        'description',
        'claim_status',
        'appointment_id',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($warrantyClaim) {
            $warrantyClaim->id = Uuid::generate()->string;
    	});
    }

    public function appointment()
    {
        return $this->hasOne(
            'App\Models\Appointment', 
            'id', 
            'appointment_id'
        );
    }
}