<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class InvoiceItem extends Model
{
    use SoftDeletes;

    protected $table = 'invoice_items';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'invoice_id',

        'item_name',
        'item_description',
        'item_quantity',
        'item_price',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($invoiceItem) {
            $invoiceItem->id = Uuid::generate()->string;
    	});
    }
}