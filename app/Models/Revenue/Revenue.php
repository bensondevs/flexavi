<?php

namespace App\Models\Revenue;

use App\Interfaces\PaymentPickupable;
use App\Models\Company\Company;
use App\Models\Receipt\Receipt;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany, MorphOne};
use Webpatser\Uuid\Uuid;

class Revenue extends Model implements PaymentPickupable
{
    use HasFactory;
    use SoftDeletes;


    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'revenues';

    /**
     * The primary key of the model
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
    public $searchableFields = ['revenue_name'];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'revenue_name',
        'amount',
        'paid_amount',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($revenue) {
            $revenue->id = Uuid::generate()->string;
        });
    }

    /**
     * Create callable static "createdBetween" method
     * This callable method will query only revenue that's
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
     * Create callable "unpaid_amount" attribute.
     * This callable attribute will return unpaid amount of revenue
     *
     * @return float
     */
    public function getUnpaidAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        $paid = $this->attributes['paid_amount'];

        return $amount - $paid;
    }

    /**
     * Get which columns is the target of payment
     * This selected column will be the column that become
     * target of substraction when payment is done
     *
     * @return string
     */
    public function getPayableColumnAttibute()
    {
        return 'paid_amount';
    }

    /**
     * Get amount that should be paid
     *
     * @return float
     */
    public function getShouldBePaidAmountAttribute()
    {
        return $this->unpaid_amount;
    }

    /**
     * Set added paid amount after the payment
     *
     * @param float  $amount
     * @return void
     */
    public function setAddedPaidAmountAttribute(float $amount)
    {
        $this->attributes['paid_amount'] += $amount;
    }

    /**
     * Create callable "is_settled" attribute.
     * This callable attribute will return boolean status of revenue settlement
     *
     * @return bool
     */
    public function getIsSettledAttribute()
    {
        return $this->getUnpaidCostAttribute() <= 0;
    }

    /**
     * Get company that owns this revenue
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get revenueable laravel-model
     *
     * @return MorphMany
     */
    public function revenueables()
    {
        return $this->morphMany(Revenueable::class, 'revenueable');
    }

    /**
     * Get receipt of current revenue
     *
     * @return MorphOne
     */
    public function receipt()
    {
        return $this->morphOne(Receipt::class, 'receiptable');
    }
}
