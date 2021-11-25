<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\InvoiceItemObserver;

class InvoiceItem extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'invoice_items';

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
     * Soft-delete touches event
     */
    protected $touches = ['invoice'];

    /**
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        'item_name',
        'description',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'invoice_id',

        'item_name',
        'description',
        'quantity',
        'quantity_unit',
        'amount',
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
        self::observe(InvoiceItemObserver::class);

    	self::creating(function ($invoiceItem) {
            $invoiceItem->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get company of this invoice item
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get invoice of this item
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Create callable attribute of "total"
     * This callable attribute will return invoice item total
     * 
     * @return double
     */
    public function getTotalAttribute()
    {   
        $quantity = $this->attributes['quantity'];
        $amount = $this->attributes['amount'];
        return $quantity * $amount;
    }

    /**
     * Recount invoice total. This method is to brute-force-ly 
     * recount the total of the invoice after 
     * addition of invoice item.
     * 
     * @return bool
     */
    public function recountInvoiceTotal()
    {
        $invoice = $this->invoice()->first();
        $invoice->countTotal();
        return $invoice->save();
    }
}