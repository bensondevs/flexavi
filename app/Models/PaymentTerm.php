<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\PaymentPickupable;
use App\Observers\PaymentTermObserver;
use App\Enums\PaymentTerm\PaymentTermStatus as Status;

class PaymentTerm extends Model implements PaymentPickupable
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'payment_terms';

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
    protected $searchable = [
        'term_name',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'invoice_id',
        'term_name',
        'status',
        'amount',
        'due_date',
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
        self::observe(PaymentTermObserver::class);

    	self::creating(function ($paymentTerm) {
            $paymentTerm->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable method overdue() to query only
     * payment terms that's has been overdue by current time.
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue(Builder $query)
    {
        return $query->where('due_date', '<=', today());
    }

    /**
     * Create callable "status_description" attribute
     * This callable attribute will return status enum description
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Create callable "formatted_amount" attribute
     * This callable attribute will return formatted amount in money format
     * 
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        return money_format('%(#1n', $this->attributes['amount']);
    }

    /**
     * Create callable "human_due_date" attribute
     * This callable attribute will return human date for user to read easier
     * 
     * @return string
     */
    public function getHumanDueDateAttribute()
    {
        $dueDate = $this->attributes['due_date'];
        return carbon()->parse($dueDate)->format('M d, Y');
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
        return 'amount';
    }

    /**
     * Get amount that should be paid
     * 
     * @return double
     */
    public function getShouldBePaidAmountAttribute()
    {
        if ($this->attributes['status'] == Status::Unpaid) {
            return 0;
        }

        return $this->attributes['amount'];
    }

    /**
     * Set added paid amount after the payment
     * 
     * @param float
     * @return void
     */
    public function setAddedPaidAmountAttribute(float $amount)
    {
        if ($this->attributes['amount'] <= $amount) {
            return $this->attributes['status'] = Status::Paid;
        }

        abort(403, 'Cannot pay less than the requested amount of payment term.');
    }

    /**
     * Get payment term invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get company that owns the payment term
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Collect all possible statuses of the payment terms
     * for select-options items
     * 
     * @return array
     */
    public static function collectAllStatuses()
    {
        return Status::asSelectArray();
    }

    /**
     * Recount total of payment terms from parent invoice
     * 
     * @return bool
     */
    public function recountInvoiceTermsTotal()
    {
        $invoice = $this->invoice()->first();
        $invoice->countTermsTotal();
        return $invoice->save();
    }
}