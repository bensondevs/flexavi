<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentWorker extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'appointment_workers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'appointment_id',
        'employee_id',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($appointmentWorker) {
            $appointmentWorker->id = Uuid::generate()->string;
    	});
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}