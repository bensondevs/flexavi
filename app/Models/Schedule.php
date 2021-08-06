<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class Schedule extends Model
{
    use SoftDeletes;
    use Searchable;

    protected $table = 'schedules';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'activity_name',
        'start',
        'end',
        'include_weekend',
        'start_money',
    ];

    protected $searchable = [
        'activity_name',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($schedule) {
            $schedule->id = Uuid::generate()->string;
    	});
    }

    public function employee()
    {
        return $this->hasManyThrough(
            Employee::class,
            ScheduleEmployee::class
        );
    }

    public function car()
    {
        return $this->hasManyThrough(
            Car::class,
            ScheduleCar::class
        );
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}