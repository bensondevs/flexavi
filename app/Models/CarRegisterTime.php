<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function scopeShouldOutBetween(Builder $query, $start, $end = null)
    {
        if ($end === null) $end = now();

        return $query->where('shoud_out_at', '>=', $start)
            ->where('shoud_out_at', '<=', $end);
    }

    public function scopeShouldReturnBetween(Builder $query, $start, $end = null)
    {
        if ($end === null) $end = now();

        return $query->where('shoud_return_at', '>=', $start)
            ->where('shoud_return_at', '<=', $end);
    }

    public function scopeMarkedOutBetween(Builder $query, $start, $end = null)
    {
        if ($end === null) $end = now();

        return $query->where('marked_out_at', '>=', $start)
            ->where('marked_out_at', '<=', $end);
    }

    public function scopeMarkedOut(Builder $query)
    {
        return $query->whereNotNull('marked_out_at');
    }

    public function scopeMarkedReturned(Builder $query)
    {
        return $query->whereNotNull('marked_return_at');
    }

    public function scopeMarkedReturnBetween(Builder $query, $start, $end = null)
    {
        if ($end === null) $end = now();

        return $query->where('marked_return_at', '>=', $start)
            ->where('marked_return_at', '<=', $end);
    }

    public function getIsOutEarlyAttribute()
    {
        $should = carbon($this->attributes['should_out_at']);
        $marked = carbon($this->attributes['marked_out_at']);

        return $marked < $should;
    }

    public function getIsOutLateAttribute()
    {
        $should = carbon($this->attributes['should_out_at']);
        $marked = carbon($this->attributes['marked_out_at']);

        return $marked > $should;
    }

    public function getIsReturnEarlyAttibute()
    {
        $should = carbon($this->attributes['should_return_at']);
        $marked = carbon($this->attributes['marked_return_at']);

        return $marked < $should;
    }

    public function getIsReturnLateAttribute()
    {
        $should = carbon($this->attributes['should_return_at']);
        $marked = carbon($this->attributes['marked_return_at']);

        return $marked > $should;
    }

    public function getLateOutDifferenceAttribute()
    {
        $should = carbon($this->attributes['should_out_at']);
        $marked = carbon($this->attributes['marked_out_at']);

        return $should->diffInMinutes($marked);
    }

    public function getEarlyLateDifferenceAttribute()
    {
        $should = carbon($this->attributes['should_return_at']);
        $marked = carbon($this->attributes['marked_return_at']);

        return $marked->diffInMinutes($should);
    }

    public function getLateReturnDifferenceAttribute()
    {
        $should = carbon($this->attributes['should_return_at']);
        $marked = carbon($this->attributes['marked_return_at']);

        return $marked->diffInMinutes($should);
    }

    public function getEarlyReturnDifferenceAttribute()
    {
        $should = carbon($this->attributes['should_return_at']);
        $marked = carbon($this->attributes['marked_return_at']);

        return $should->diffInMinutes($marked);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function worklist()
    {
        return $this->belongsTo(Worklist::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}