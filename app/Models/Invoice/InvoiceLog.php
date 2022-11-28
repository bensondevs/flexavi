<?php

namespace App\Models\Invoice;

use App\Observers\InvoiceLogObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InvoiceLog extends Model
{
    use HasFactory;

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
    public array $searchableFields = [];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'invoice_logs';

    /**
     * The primary key of the model
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * cast attributes
     *
     * @var string[]
     */
    protected $casts = [
        'message' => 'array',
    ];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'actor_type',
        'actor_id',
        'message',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(InvoiceLogObserver::class);
    }

    /**
     * Get the invoice that owns the InvoiceLog
     *
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the actor that owns the InvoiceLog
     *
     * @return MorphTo
     */
    public function actor(): MorphTo
    {
        return $this->morphTo();
    }
}
