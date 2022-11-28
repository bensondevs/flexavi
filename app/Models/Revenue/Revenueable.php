<?php

namespace App\Models\Revenue;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};
use Webpatser\Uuid\Uuid;


class Revenueable extends Model
{

    use HasFactory;
    use SoftDeletes;


    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'revenueables';

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
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = ['revenue_id', 'revenueable_type', 'revenueable_id'];

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
        self::creating(function ($revenueable) {
            $revenueable->id = Uuid::generate()->string;
        });
    }

    /**
     * Get revenue connected through this model pivot
     *
     * @return BelongsTo
     */
    public function revenue()
    {
        return $this->belongsTo(Revenue::class);
    }

    /**
     * Get revenueable model connected through this model
     * Possible revenueables:
     * - \App\Models\Work
     * - \App\Models\InvoiceItem
     *
     * @return MorphTo
     */
    public function revenueable()
    {
        return $this->morphTo();
    }
}
