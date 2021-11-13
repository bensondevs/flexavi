<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Znck\Eloquent\Traits\BelongsToThrough;

use App\Enums\CarRegisterTimeEmployee\PassangerType;

class CarRegisterTimeEmployee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use BelongsToThrough;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'car_register_time_employees';

    /**
     * Table name primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamp recording
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use increment or not
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        //
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var bool
     */
    protected $fillable = [
        'company_id',
        'car_register_time_id',
        'employee_id',
        'passanger_type',
    ];

    /**
     * Function that will be run whenever event happened
     * 
     * @return  void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($carRegisterTimeEmployee) {
            $carRegisterTimeEmployee->id = Uuid::generate()->string;
    	});
    }

    public function getPassangerTypeDescriptionAttribute()
    {
        $type = $this->attributes['passanger_type'];
        return PassangerType::getDescription($type);
    }

    public function setEmployeeAttribute(Employee $employee)
    {
        $this->attributes['employee_id'] = $employee->id;
        $this->attributes['company_id'] = $employee->company_id;
    }

    public function setTimeAttribute(CarRegisterTime $time)
    {
        $this->attributes['car_register_time_id'] = $time->id;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function carRegisterTime()
    {
        return $this->belongsTo(CarRegisterTime::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class, CarRegisterTime::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function setAsDriver()
    {
        $this->attributes['passanger_type'] = PassangerType::Driver;
        return $this->save();
    }

    public function setAsPassanger()
    {
        $this->attributes['passanger_type'] = PassangerType::Passanger;
        return $this->save();
    }
}