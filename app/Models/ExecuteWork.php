<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\ExecuteWork\ExecuteWorkStatus;
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
        'description',
        'note',
        'finish_note',
    ];

    protected $fillable = [
        'company_id',
        'work_id',
        'appointment_id',
        'sub_appointment_id',
        'description',
        'note',
        'finish_note',
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

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return ExecuteWorkStatus::getDescription($status);
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

    public function previousExecuteWork()
    {
        return $this->belongsTo(self::class, 'previous_execute_work_id');
    }

    public function photos()
    {
        return $this->hasMany(ExecuteWorkPhoto::class);
    }

    public function beforeWorkPhotos()
    {
        return $this->photos()->where('photo_condition_type', PhotoConditionType::Before);
    }

    public function afterWorkPhotos()
    {
        return $this->photos()->where('photo_condition_type', PhotoConditionType::After);
    }

    public function finish(array $finishData)
    {
        $this->attributes['finish_note'] = isset($finishData['finish_note']) ?
            $finishData['finish_note'] : null;
        $this->attributes['finished_at'] = now();
        $this->attributes['status'] = ExecuteWorkStatus::Finished;
        return $this->save();
    }
}