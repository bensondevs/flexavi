<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType;

class ExecuteWork extends Model
{
    protected $table = 'execute_works';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'appointment_id',
        'work_id',
        'is_finished',
        'is_continuation',
        'previous_execute_work_id',
        'note',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($executeWork) {
            $executeWork->id = Uuid::generate()->string;
    	});
    }

    public function beforeWorkPhotos()
    {
        return $this->hasMany('App\Models\ExecuteWorkPhoto', 'execute_work_id', 'id')
            ->where('photo_condition_type', PhotoConditionType::Before);
    }

    public function afterWorkPhotos()
    {
        return $this->hasMany('App\Models\ExecuteWorkPhoto', 'execute_work_id', 'id')
            ->where('photo_condition_type', PhotoConditionType::After);
    }

    public function appointment()
    {
        return $this->belongsTo('App\Models\Appointment', 'id', 'appointment_id');
    }

    public function work()
    {
        return $this->hasOne('App\Models\Work', 'work_id', 'id');
    }
}