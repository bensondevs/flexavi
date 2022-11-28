<?php

namespace App\Models\Cost;

use App\Enums\Cost\CostableType;
use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\Receipt\Receipt;
use App\Models\Workday\Workday;
use App\Models\Worklist\Worklist;
use Fidum\EloquentMorphToOne\HasMorphToOne;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphOne, MorphToMany};
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Webpatser\Uuid\Uuid;

class Cost extends Model
{

    use HasFactory;
    use SoftDeletes;

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
    public $searchableFields = ['cost_name'];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = ['company_id', 'cost_name', 'amount', 'paid_amount'];

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
     * Create callable static "createdBetween" method
     * This callable method will query only cost that's
     * created between certain range of time
     *
     * @param Builder  $query
     * @param mixed  $start
     * @param mixed  $end
     * @return Builder
     */
    public function scopeCreatedBetween(Builder $query, $start, $end)
    {
        return $query
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end);
    }

    /**
     * Define the searchable query
     *
     * @param Builder $query
     * @return Builder
     */
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['company']);
    }

    /**
     * Create attribute of `unpaid_amount` and get unpaid amount
     *
     * @return float
     */
    public function getUnpaidAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        if (!($paid = $this->attributes['paid_amount'])) {
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
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get appointments attached to cost
     *
     * @return MorphToMany
     */
    public function appointments()
    {
        return $this->morphedByMany(Appointment::class, 'costable');
    }

    /**
     * Get first appointment attached
     *
     * @return Appointment|null
     */
    public function getAppointmentAttribute()
    {
        return $this->appointments->first();
    }

    /**
     * Get list of worklists attached to the cost
     *
     * @return MorphToMany
     */
    public function worklists()
    {
        return $this->morphedByMany(Worklist::class, 'costable');
    }

    /**
     * Get first attached worklist of the cost
     *
     * @return Worklist|null
     */
    public function getWorklistAttribute()
    {
        return $this->worklists->first();
    }

    /**
     * Get list of attached workdays of the cost
     *
     * @return MorphToMany
     */
    public function workdays()
    {
        return $this->morphedByMany(Workday::class, 'costable');
    }

    /**
     * Get first workday attached to the cost
     *
     * @return Workday|null
     */
    public function getWorkdayAttribute()
    {
        return $this->workdays->first();
    }

    /**
     * Get pivot costable
     *
     * @return HasMany
     */
    public function costables()
    {
        return $this->hasMany(Costable::class);
    }

    /**
     * Get attached receipt to the cost
     *
     * @return MorphOne
     */
    public function receipt()
    {
        return $this->morphOne(Receipt::class, 'receiptable');
    }
}
