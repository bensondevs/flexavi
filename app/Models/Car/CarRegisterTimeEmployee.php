<?php

namespace App\Models\Car;

use App\Enums\CarRegisterTimeEmployee\PassangerType;
use App\Models\Company\Company;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;
use Znck\Eloquent\Traits\BelongsToThrough;

class CarRegisterTimeEmployee extends Model
{

    use HasFactory;
    use SoftDeletes;

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
    public $searchableFields = [];

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
     * Define the searchable query
     *
     * @param Builder $query
     * @return Builder
     */
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['company', 'car', 'employee']);
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
     *
     * @return void
     */
    public function setEmployeeAttribute(Employee $employee)
    {
        $this->attributes['employee_id'] = $employee->id;
        $this->attributes['company_id'] = $employee->company_id;
    }

    /**
     * Create assignable attribute of `time` which will fill
     * this model attribute of `car_register_time_id`
     *
     * @return void
     */
    public function setTimeAttribute(CarRegisterTime $time)
    {
        $this->attributes['car_register_time_id'] = $time->id;
    }

    /**
     * Get car register time employee company
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get car register time
     *
     * @return BelongsTo
     */
    public function carRegisterTime()
    {
        return $this->belongsTo(CarRegisterTime::class);
    }

    /**
     * Get car assigned to this model
     *
     * @return BelongsTo
     */
    public function car()
    {
        return $this->belongsTo(Car::class, CarRegisterTime::class);
    }

    /**
     * Get registered employee
     *
     * @return BelongsTo
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
