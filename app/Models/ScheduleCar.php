<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class ScheduleCar extends Model
{
    protected $table = 'schedule_cars';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'schedule_id',
        'car_id',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($scheduleCar) {
            $scheduleCar->id = Uuid::generate()->string;
    	});
    }

    public function car()
    {
        return $this->hasOne(
            'App\Models\Car', 
            'id', 
            'car_id'
        );
    }
}