<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType;

use App\Observers\ExecuteWorkObserver;

class ExecuteWork extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'execute_works';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'note'
    ];

    protected $fillable = [
        'company_id',
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
        self::observe(ExecuteWorkObserver::class);

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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}