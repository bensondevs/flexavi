<?php

namespace App\Models\Calculation;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webpatser\Uuid\Uuid;

class Calculation extends Model
{

    use HasFactory;
    use SoftDeletes;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'calculations';

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
    protected $fillable = [
        'calculationable_type',
        'calculationable_id',
        'calculation',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'calculation' => 'json',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($calculation) {
            $calculation->id = Uuid::generate()->string;
        });
    }

    /**
     * Get calculationable model
     *
     * @return MorphTo
     */
    public function calculationable()
    {
        return $this->morphTo();
    }
}
