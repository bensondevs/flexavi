<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use App\Models\StorageFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\ReceiptObserver;

class Receipt extends Model
{
    use HasFactory;
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
     * Possible morph types list
     * 
     * @var  array
     */
    const MORPHED_TYPES = [
        Cost::class,
        Revenue::class,
        PaymentTerm::class,
        PaymentPickup::class,
        PaymentReminder::class,
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
    }

    /**
     * Get receiptable attached for receipt
     */
    public function receiptable()
    {
        return $this->morphTo();
    }

    /**
     * Get the company owner of the receipt
     */
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

    /**
     * Guess the model type of the receiptable
     * 
     * @static
     * @param  string  $clue
     */
    public static function guessType(string $clue)
    {
        foreach (self::MORPHED_TYPES as $type) {
            // Clue is perfectly match with specified types
            if ($clue == $type) return $type;

            // Clue is the lower case of type
            if (strtolower($clue) == strtolower($type)) return $type;

            // Clue is the pure class of type
            if ($clue == get_pure_class($type)) return $type;

            // Clue is the pure lower case of type
            if (strtolower($clue) == get_lower_class($type)) return $type;

            // Clue is the plural class of type
            if (strtolower($clue) == get_plural_lower_class($type)) return $type;
        }

        return self::MORPHED_TYPES[rand(0, count(self::MORPHED_TYPES))];
    }
}