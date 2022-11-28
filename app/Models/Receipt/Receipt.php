<?php

namespace App\Models\Receipt;

use App\Models\Company\Company;
use App\Models\Cost\Cost;
use App\Models\PaymentPickup\PaymentPickup;
use App\Models\PaymentPickup\PaymentReminder;
use App\Models\PaymentPickup\PaymentTerm;
use App\Models\Revenue\Revenue;
use App\Observers\ReceiptObserver;
use App\Rules\Helpers\Media;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Receipt extends Model
{

    use HasFactory;
    use SoftDeletes;


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
    public $searchableFields = ['description'];

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
     *
     * @return MorphTo
     */
    public function receiptable()
    {
        return $this->morphTo();
    }

    /**
     * Get the company owner of the receipt
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Perform download action of the image file
     *
     * @throws HttpException
     * @return StreamedResponse
     */
    public function downloadReceiptImage()
    {
        if (Storage::missing("receipts/$this->receipt_path")) {
            abort(404);
        }

        return Storage::download(
            "receipts/$this->receipt_path"
        );
    }

    /**
     * Create settable attribute of "receipt_image"
     * This settable attribute will set the "receipt_path" and upload
     * the file to the storage
     *
     * @param UploadedFile  $file
     * @return void
     */
    public function setReceiptImageAttribute(UploadedFile $file)
    {
        $filename = Media::randomFilename($file);
        Storage::putFileAs('receipts', $file, $filename);
        $this->attributes['receipt_path'] = $filename;
    }

    /**
     * Create callable attribute of "receipt_url"
     * This callable attribute will generate url from the image path
     *
     * @return  string|null
     */
    public function getReceiptUrlAttribute()
    {
        if (Storage::missing("receipts/$this->receipt_path")) {
            return null;
        }

        return Storage::url("receipts/$this->receipt_path");
    }

    /**
     * Guess the model type of the receiptable
     *
     * @static
     * @param  string  $clue
     * @return string
     */
    public static function guessType(string $clue)
    {
        foreach (self::MORPHED_TYPES as $type) {
            // Clue is perfectly match with specified types
            if ($clue == $type) {
                return $type;
            }

            // Clue is the lower case of type
            if (strtolower($clue) == strtolower($type)) {
                return $type;
            }

            // Clue is the pure class of type
            if ($clue == get_pure_class($type)) {
                return $type;
            }

            // Clue is the pure lower case of type
            if (strtolower($clue) == get_lower_class($type)) {
                return $type;
            }

            // Clue is the plural class of type
            if (strtolower($clue) == get_plural_lower_class($type)) {
                return $type;
            }
        }

        return self::MORPHED_TYPES[rand(0, count(self::MORPHED_TYPES))];
    }
}
