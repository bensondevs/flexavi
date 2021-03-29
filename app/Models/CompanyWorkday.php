<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class CompanyWorkday extends Model
{
    protected $table = 'company_workdays';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'schedules_json',
        'include_weekend',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($companyWorkday) {
            $companyWorkday->id = Uuid::generate()->string;
    	});
    }

    public function setScheduleAttribute(array $value)
    {
        $schedule = [
            'sunday' => json_encode($value['sunday']),
            'monday' => json_encode($value['monday']),
            'tuesday' => json_encode($value['tuesday']),
            'wednesday' => json_encode($value['wednesday']),
            'thursday' => json_encode($value['thursday']),
            'friday' => json_encode($value['friday']),
            'saturday' => json_encode($value['saturday']),
        ];

        $this->attributes['schedules_json'] = json_encode($schedule);
    }

    public function getScheduleAttribute()
    {
        $schedule = json_decode($this->attributes['schedules_json'], true);
        foreach ($schedule as $key => $daily)
            $schedule[$key] = json_decode($daily, true);

        return $schedule;
    }
}