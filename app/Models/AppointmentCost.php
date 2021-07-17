<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class AppointmentCost extends Model
{
    protected $table = 'appointment_costs';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'appointment_id',

        'cost_name',
        'cost',
        'paid_cost',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($cost) {
            $cost->id = Uuid::generate()->string;
    	});
    }

    public function getUnpaidCostAttribute()
    {
        $cost = $this->attributes['cost'];
        $paid = $this->attributes['paid_cost'];

        return $cost - $paid;
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}