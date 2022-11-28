<?php

namespace App\Models\Worklist;

use App\Models\Car\Car;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class WorklistCar extends Model
{

    use HasFactory;
    use SoftDeletes;


    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'worklist_cars';

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
        'worklist_id',
        'car_id',
        'employee_in_charge_id',
        'should_return_at',
        'returned_at',
    ];

    /**
     * Function that will be run whenever event happened
     *
     * @return  void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(\App\Observers\WorklistCarObserver::class);
    }

    /**
     * Get the worklist
     *
     * @return BelongsTo
     */
    public function worklist()
    {
        return $this->belongsTo(Worklist::class);
    }

    /**
     * Get the worklist car
     *
     * @return BelongsTo
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Get the employee in charge
     *
     * @return BelongsTo
     */
    public function employeeInCharge()
    {
        return $this->belongsTo(Employee::class, 'employee_in_charge_id');
    }

    /**
     * Assign car into worklist
     *
     * @param Worklist $worklist
     * @param Car $car
     * @return void
     */
    public static function assignCar(Worklist $worklist, Car $car)
    {
        WorklistCar::create([
            'worklist_id' => $worklist->id,
            'car_id' => $car->id,
        ]);
    }
}
