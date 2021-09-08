<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;

use App\Enums\Employee\EmployeeType;
use App\Enums\Employee\EmploymentStatus;

class Employee extends Model
{
    use Searchable;
    use SoftDeletes;
    use HasRelationships;

    protected $table = 'employees';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'title',
    ];

    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'employee_type',
        'employment_status',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($employee) {
            $employee->id = Uuid::generate()->string;
    	});
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addresses()
    {
        return $this->hasManyThrough(
            Address::class, 
            User::class,
            'id',
            'user_id',
            'user_id',
        );
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, AppointmentEmployee::class);
    }

    public function inspectors()
    {
        return $this->hasMany(Inspector::class);
    }

    public function inspections()
    {
        return $this->belongsToMany(Inspection::class, Inspector::class);
    }

    public function todayInspections()
    {
        $now = carbon()->now();
        $startOfToday = $now->startOfDay();
        $endOfToday = $now->endOfDay();

        return $this->inspections()->whereHas(
            'appointment', 
            function (Builder $appointment) use ($startOfToday, $endOfToday) {
                $appointment
                    ->where('start', '>=', $startOfToday)
                    ->where('end', '<=', $endOfToday);
            }
        );
    }

    public function getEmployeeTypeDescriptionAttribute()
    {
        $type = $this->attributes['employee_type'];
        return EmployeeType::getDescription($type);
    }

    public function getEmploymentStatusDescriptionAttribute()
    {
        $status = $this->attributes['employment_status'];
        return EmploymentStatus::getDescription($status);
    }

    public static function collectAllTypes()
    {
        return EmployeeType::asSelectArray();
    }

    public static function collectAllEmploymentStatus()
    {
        return EmploymentStatus::asSelectArray();
    }
}