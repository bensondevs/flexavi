<?php

namespace App\Models\User;

use App\Observers\UserSocialiteAccountObserver;
use Illuminate\Database\Eloquent\{Model, Relations\BelongsTo, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserSocialiteAccount extends Model
{

    use HasFactory;
    use SoftDeletes;

    /**
     * Set timestamp each time model is saved
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * Set whether primary key use incrementing value or not
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * Set which columns are searchable
     *
     * @var array
     */
    public $searchableFields = [];

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'user_socialite_accounts';

    /**
     * Table name primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'vendor_user_id'];

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
        self::observe(UserSocialiteAccountObserver::class);
    }

    /**
     * Get user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
