<?php

namespace App\Models\Quotation;

use App\Observers\QuotationLogObserver;
use App\Services\Quotation\QuotationLogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class QuotationLog extends Model
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
    protected $table = 'quotation_logs';

    /**
     * The primary key of the model
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'quotation_id',
        'actor_type',
        'actor_id',
        'log_name',
        'properties',
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
        self::observe(QuotationLogObserver::class);
    }

    /**
     * Get the quotation that owns the QuotationLog
     *
     * @return BelongsTo
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'id');
    }

    /**
     * Get all the owning actor models.
     *
     * @return MorphTo
     */
    public function actor(): MorphTo
    {
        return $this->morphTo(
            'user',
            \App\Models\User\User::class,
        );
    }


    /**
     * Get formatted message
     *
     * @return ?string
     */
    public function getMessageAttribute(): ?string
    {
        return QuotationLogService::formatMessage($this);
    }
}
