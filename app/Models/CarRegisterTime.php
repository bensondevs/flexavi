<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\CarRegisterTimeEmployee\PassangerType;

use App\Observers\CarRegisterTimeObserver;

class CarRegisterTime extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'car_register_times';

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
        'worklist_id',
        'car_id',

        'should_out_at',
        'should_return_at',

        'marked_out_at',
        'marked_return_at',
    ];

    /**
     * Function that will be run whenever event happened
     * 
     * @return  void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(CarRegisterTimeObserver::class);

    	self::creating(function ($carRegisterTime) {
            $carRegisterTime->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable method of shouldOutBetween($start, $end)
     * And query only car_register_times which set should be out within range
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @param mixed  $string
     * @param mixed  $end
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeShouldOutBetween(Builder $query, $start, $end = null)
    {
        if ($end === null) $end = now();

        return $query->where('shoud_out_at', '>=', $start)
            ->where('shoud_out_at', '<=', $end);
    }

    /**
     * Create callable method of shouldReturnBetween($start, $end)
     * And query only car_register_times which set should be return within range
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @param mixed  $string
     * @param mixed  $end
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeShouldReturnBetween(Builder $query, $start, $end = null)
    {
        if ($end === null) $end = now();

        return $query->where('shoud_return_at', '>=', $start)
            ->where('shoud_return_at', '<=', $end);
    }

    /**
     * Create callable method of markedOutBetween($start, $end)
     * And query only car_register_times which set marked out within range
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @param mixed  $string
     * @param mixed  $end
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeMarkedOutBetween(Builder $query, $start, $end = null)
    {
        if ($end === null) $end = now();

        return $query->where('marked_out_at', '>=', $start)
            ->where('marked_out_at', '<=', $end);
    }

    /**
     * Create callable method of markedReturnBetween($start, $end)
     * And query only car_register_times which set marked return within range
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @param mixed  $string
     * @param mixed  $end
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeMarkedReturnBetween(Builder $query, $start, $end = null)
    {
        if ($end === null) $end = now();

        return $query->where('marked_return_at', '>=', $start)
            ->where('marked_return_at', '<=', $end);
    }

    /**
     * Create callable method of markedOut() 
     * And query only car_register_times which already marked out
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeMarkedOut(Builder $query)
    {
        return $query->whereNotNull('marked_out_at');
    }

    /**
     * Create callable method of markedReturned() 
     * And query only car_register_times which already marked returned
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeMarkedReturned(Builder $query)
    {
        return $query->whereNotNull('marked_return_at');
    }

    /**
     * Create callable attribute of `is_out_early` and get
     * whether the car_register_time is marked out earlier
     * than the `should_be_out` column value
     * 
     * @return bool
     */
    public function getIsOutEarlyAttribute()
    {
        $should = carbon($this->attributes['should_out_at']);
        $marked = carbon($this->attributes['marked_out_at']);

        return $marked < $should;
    }

    /**
     * Create callable attribute of `is_out_late` and get
     * whether the car_register_time is marked out later
     * than the `should_be_out` column value
     * 
     * @return bool
     */
    public function getIsOutLateAttribute()
    {
        $should = carbon($this->attributes['should_out_at']);
        $marked = carbon($this->attributes['marked_out_at']);

        return $marked > $should;
    }

    /**
     * Create callable attribute of `is_return_early` and get
     * whether the car_register_time is marked out earlier
     * than the `should_return_at` column value
     * 
     * @return bool
     */
    public function getIsReturnEarlyAttibute()
    {
        $should = carbon($this->attributes['should_return_at']);
        $marked = carbon($this->attributes['marked_return_at']);

        return $marked < $should;
    }

    /**
     * Create callable attribute of `is_return_late` and get
     * whether the car_register_time is marked out later
     * than the `should_return_at` column value
     * 
     * @return bool
     */
    public function getIsReturnLateAttribute()
    {
        $should = carbon($this->attributes['should_return_at']);
        $marked = carbon($this->attributes['marked_return_at']);

        return $marked > $should;
    }

    /**
     * Create callable attribute of `late_out_difference` and get
     * how much time late in minutes
     * 
     * @return int
     */
    public function getLateOutDifferenceAttribute()
    {
        $should = carbon($this->attributes['should_out_at']);
        $marked = carbon($this->attributes['marked_out_at']);

        return $should->diffInMinutes($marked);
    }

    /**
     * Create callable attribute of `early_out_difference` and get
     * how much time late in minutes
     * 
     * @return int
     */
    public function getLateReturnDifferenceAttribute()
    {
        $should = carbon($this->attributes['should_return_at']);
        $marked = carbon($this->attributes['marked_return_at']);

        return $marked->diffInMinutes($should);
    }

    /**
     * Get company of the car register time
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get worklist of the car register time
     */
    public function worklist()
    {
        return $this->belongsTo(Worklist::class);
    }

    /**
     * Get car of the car register time
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Get list of assigned employees
     */
    public function assignedEmployees()
    {
        return $this->hasMany(CarRegisterTimeEmployee::class);
    }

    /**
     * Get current driver
     */
    public function currentDriver()
    {
        $assignedEmployees = $this->assignedEmployees();
        if ($this->relationLoaded('assignedEmployees')) {
            $assignedEmployees = $this->assignedEmployees;
        }

        return $this->assignedEmployees
            ->where('passanger_type', PassangerType::Driver)
            ->first();
    }

    /**
     * Get whether the registered time has driver
     */
    public function hasDriver()
    {
        return $this->assignedEmployees()
            ->where('passanger_type', PassangerType::Driver)
            ->exists();
    }

    /**
     * Collect all car register times passanger types
     * 
     * @static
     * @return array
     */
    public static function collectAllPassangerTypes()
    {
        return PassangerType::asSelectArray();
    }
}