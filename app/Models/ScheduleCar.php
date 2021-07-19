<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class ScheduleCar extends Model
{
    use SoftDeletes;
    use Searchable;

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
        return $this->belongsTo(Car::class);
    }
}