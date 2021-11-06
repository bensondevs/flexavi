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

    protected $table = 'invoice_items';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $touches = ['invoice'];

    protected $fillable = [
        'company_id',
        'invoice_id',

        'item_name',
        'description',
        'quantity',
        'quantity_unit',
        'amount',
    ];

    protected $searchable = [
        'item_name',
        'description',
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(InvoiceItemObserver::class);

    	self::creating(function ($invoiceItem) {
            $invoiceItem->id = Uuid::generate()->string;
    	});
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getTotalAttribute()
    {   
        $quantity = $this->attributes['quantity'];
        $amount = $this->attributes['amount'];
        return $quantity * $amount;
    }

    public function recountInvoiceTotal()
    {
        $invoice = $this->invoice()->first();
        $invoice->countTotal();
        $invoice->save();
    }
}