<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorklistCar extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

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
    protected $searchable = [
        //
    ];

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

    	self::creating(function ($worklistCar) {
            $worklistCar->id = Uuid::generate()->string;
    	});
    }

    public function worklist()
    {
        return $this->belongsTo(Worklist::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function employeeInCharge()
    {
        return $this->belongsTo(Employee::class, 'employee_in_charge_id');
    }
}