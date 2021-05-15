<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Appointmentable extends Model
{
    protected $table = 'appointmentables';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'appointment_id',
        'appoinmentable_id',
        'appointmentable_type',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($appointmentable) {
            $appointmentable->id = Uuid::generate()->string;
    	});
    }

    public function pivot()
    {
        return $this->hasOne(
            $this->attributes['appointmentable_type'],
            $this->attributes['appointmentable_id'],
            'id'
        );
    }
}