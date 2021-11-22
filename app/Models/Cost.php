<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Fidum\EloquentMorphToOne\HasMorphToOne;
use Fidum\EloquentMorphToOne\MorphToOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\Cost\CostableType;

class Cost extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use HasRelationships;
    use HasMorphToOne;

    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'costs';

    /**
     * Table name primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set timestamp each time model is saved
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use incrementing value or not
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
        'cost_name',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',

        'cost_name',
        'amount',
        'paid_amount',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($cost) {
            $cost->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create attribute of `unpaid_amount` and get unpaid amount
     * 
     * @return double
     */
    public function getUnpaidAmountAttribute()
    {
        $amount = $this->attributes['amount'];

        if (! $paid = $this->attributes['paid_amount']) {
            return 0;
        }

        return $amount - $paid;
    }

    /**
     * Create attribute of `is_settled` 
     * and get status whether cost is settled or not
     * 
     * @return bool
     */
    public function getIsSettledAttribute()
    {
        $unpaid = $this->getUnpaidCostAttribute();
        return $unpaid <= 0;
    }

    /**
     * Create attribute of `receipt_file` and get cost receipt path
     * 
     * @return string
     */
    public function setReceiptFileAttribute($receiptFile)
    {
        $directory = 'uploads/costs/receipts/';
        $receipt = uploadFile($receiptFile, $directory);

        return $this->attributes['receipt_path'] = $receipt->path;
    }

    /**
     * Get all costable types as array
     * 
     * @return array
     */
    public static function collectAllCostableTypes()
    {
        return CostableType::asSelectArray();
    }

    /**
     * Get company of the cost
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get appointments attached to cost
     */
    public function appointments()
    {
        return $this->morphedByMany(Appointment::class, 'costable');
    }

    /**
     * Get first appointment attached
     */
    public function getAppointmentAttribute()
    {
        return $this->appointments->first();
    }

    /**
     * Get list of worklists attached to the cost
     */
    public function worklists()
    {
        return $this->morphedByMany(Worklist::class, 'costable');
    }

    /**
     * Get first attached worklist of the cost
     * 
     * @return \App\Models\Worklist
     */
    public function getWorklistAttribute()
    {
        return $this->worklists->first();
    }

    /**
     * Get list of attached workdays of the cost
     */
    public function workdays()
    {
        return $this->morphedByMany(Workday::class, 'costable');
    }

    /**
     * Get first workday attached to the cost
     * 
     * @return \App\Models\Workday
     */
    public function getWorkdayAttribute()
    {
        return $this->workdays->first();
    }

    /**
     * Get pivot costable
     */
    public function costables()
    {
        return $this->hasMany(Costable::class);
    }

    /**
     * Get attached receipt to the cost
     */
    public function receipt()
    {
        return $this->morphOne(Receipt::class, 'receiptable');
    }
}