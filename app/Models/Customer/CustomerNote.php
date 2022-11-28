<?php

namespace App\Models\Customer;

use App\Observers\CustomerNoteObserver;
use App\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerNote extends Model
{
    use HasFactory, SoftDeletes, Searchable;

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
    public array $searchableFields = ['note', 'created_at'];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'customer_notes';

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
        'customer_id',
        'note',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(CustomerNoteObserver::class);
    }

    /**
     * Get customer
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Create callable `date`
     * this attribute will return date from created_at
     *
     * @return string
     */
    public function getDateAttribute(): string
    {
        $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at']);
        return $createdAt->format('Y-m-d');
    }

    /**
     * Create callable `time`
     * this attribute will return time from created_at
     *
     * @return string
     */
    public function getTimeAttribute(): string
    {
        $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at']);
        return $createdAt->format('g:i A');
    }
}
