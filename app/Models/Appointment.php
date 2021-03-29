<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'customer_id',

        'start',
        'end',
        'include_weekend',
        'appointment_type',
        'appointment_status',
        'note',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($appointment) {
            $appointment->id = Uuid::generate()->string;
    	});
    }

    public function customer()
    {
        return $this->hasOne(
            'App\Models\Customer',
            'customer_id',
            'id'
        );
    }
}