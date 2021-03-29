<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class ScheduleEmployee extends Model
{
    protected $table = 'schedule_employees';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'schedule_id',
        'employee_id',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($scheduleEmployee) {
            $scheduleEmployee->id = Uuid::generate()->string;
    	});
    }

    public function employee()
    {
        return $this->hasOne(
            'App\Models\Employee', 
            'id', 
            'employee_id'
        );
    }
}