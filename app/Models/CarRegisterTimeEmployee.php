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

    /**
     * Create callable attribute of `passanger_type_description`
     * and get the passanger type description of the enum
     * 
     * @return string
     */
    public function getPassangerTypeDescriptionAttribute()
    {
        $type = $this->attributes['passanger_type'];
        return PassangerType::getDescription($type);
    }

    /**
     * Create assignable attribute of `employee` which will fill
     * this model attribute of `employee_id` and `company_id` 
     */
    public function setEmployeeAttribute(Employee $employee)
    {
        $this->attributes['employee_id'] = $employee->id;
        $this->attributes['company_id'] = $employee->company_id;
    }

    /**
     * Create assignable attribute of `time` which will fill
     * this model attribute of `car_register_time_id`
     */
    public function setTimeAttribute(CarRegisterTime $time)
    {
        $this->attributes['car_register_time_id'] = $time->id;
    }

    /**
     * Get car register time employee company 
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get car register time
     */
    public function carRegisterTime()
    {
        return $this->belongsTo(CarRegisterTime::class);
    }

    /**
     * Get car assigned to this model
     */
    public function car()
    {
        return $this->belongsTo(Car::class, CarRegisterTime::class);
    }

    /**
     * Get registered employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Set this registered employee as driver of the car
     * 
     * @return bool
     */
    public function setAsDriver()
    {
        $this->attributes['passanger_type'] = PassangerType::Driver;
        return $this->save();
    }

    /**
     * Set this registered employee as passanger of the car
     * 
     * @return bool
     */
    public function setAsPassanger()
    {
        $this->attributes['passanger_type'] = PassangerType::Passanger;
        return $this->save();
    }
}