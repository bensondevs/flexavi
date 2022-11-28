<?php

namespace App\Models\PaymentPickup;

use App\Models\BelongsTo;
use App\Models\Invoice\Invoice;
use App\Observers\PaymentPickupItemObserver;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPickupItem extends Model
{
    use HasFactory;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'payment_pickup_items';

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
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'payment_pickup_id',
        'invoice_id',
        'payment_term_ids',
        'total_bill',
        'pickup_amount',
        'note',
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
        self::observe(PaymentPickupItemObserver::class);
    }

    /**
     * Get payment pickup of payment pickup item
     *
     * @return BelongsTo
     */
    public function paymentPicup()
    {
        return $this->belongsTo(PaymentPickup::class);
    }


    /**
     * Get invoice of payment pickup item
     *
     * @return BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Create callable "payment_term_ids" attribute
     * This attribute will return array of payment terms id
     *
     * @return array
     */
    public function getPaymentTermIdsAttribute()
    {
        return json_decode($this->attributes['payment_term_ids'], true);
    }

    /**
     * Create callable "formatted_total_bill" attribute
     * This attribute will return total bill
     * in currency formatted form.
     *
     * @return string
     */
    public function getFormattedTotalBillAttribute()
    {
        return currency_format($this->attributes['total_bill']);
    }

    /**
     * Create callable "formatted_pickup_amount" attribute
     * This attribute will return pickup amoun
     * in currency formatted form.
     *
     * @return string
     */
    public function getFormattedPickupAmountAttribute()
    {
        return currency_format($this->attributes['pickup_amount']);
    }


    /**
     * Create callable "payment_terms" attribute
     * This attribute will return array of payment terms id
     *
     * @return Collection
     */
    public function getPaymentTermsAttribute()
    {
        return PaymentTerm::whereIn('id', $this->getPaymentTermIdsAttribute())->get();
    }
}
