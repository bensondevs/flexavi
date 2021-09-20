<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class AppointmentEmployee extends Model
{
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'appointment_employees';

    /**
     * Table name primary key
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
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        //
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var bool
     */
    protected $fillable = [
        'appointment_id',
        'employee_id',
    ];

    /**
     * Function that will be run whenever event happened
     * 
     * @return  void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($appointmentEmployee) {
            $appointmentEmployee->id = Uuid::generate()->string;
    	});
    }

    public static function isExists(Appointment $appointment, Employee $employee)
    {
        return self::where('appointment_id', $appointment->id)
            ->where('employee_id', $employee->id)
            ->exists();
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