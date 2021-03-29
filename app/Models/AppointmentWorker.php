<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class AppointmentWorker extends Model
{
    protected $table = 'appointment_workers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'appointment_id',
        'employee_type',
        'employee_id',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($appointmentWorker) {
            $appointmentWorker->id = Uuid::generate()->string;
    	});
    }

    public function appointment()
    {
        return $this->hasOne(
            'App\Models\Appointment',
            'appointment_id',
            'id',
        );
    }

    public function employee()
    {
        return $this->hasOne(
            'App\Models\Employee',
            'employee_id',
            'id'
        );
    }
}