<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class AppointmentCost extends Model
{
    use SoftDeletes;
    use Searchable;

    protected $table = 'appointment_costs';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'cost_name',
    ];

    protected $fillable = [
        'appointment_id',

        'cost_name',
        'cost',
        'paid_cost',
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