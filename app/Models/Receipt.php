<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use App\Models\StorageFile;

use App\Observers\ReceiptObserver;

class Receipt extends Model
{
    use SoftDeletes;
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'receipts';

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
        'description'
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var bool
     */
    protected $fillable = [
        'company_id',

        'receiptable_type',
        'receiptable_id',
        'receipt_path',
        'description',
    ];

    /**
     * Function that will be run whenever event happened
     * 
     * @return  void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(ReceiptObserver::class);

    	self::creating(function ($receipt) {
            $receipt->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get relationship attached
     * 
     * @return  Builder
     */
    public function receiptable()
    {
        return $this->morphTo();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Upload receipt file and set the name of model attribute
     * 
     * @return  void
     */
    public function setReceiptImageAttribute($receiptFile)
    {
        $directory = 'uploads/receipts';
        $receipt = uploadFile($receiptFile, $directory);

        $this->attributes['receipt_path'] = $receipt->path;
    }

    /**
     * Get uploaded receipt as downloadable URL or return null
     * 
     * @return  string|null
     */
    public function getReceiptUrlAttribute()
    {
        if (! $path = $this->attributes['receipt_path']) {
            return '[Path not set]';
        }

        if (! $file = StorageFile::findByPath($path)) {
            return '[File not found]';
        }


        return $file->getDownloadUrl();
    }
}