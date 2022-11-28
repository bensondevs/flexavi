<?php

namespace App\Models\User;

use App\Observers\PasswordResetObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PasswordReset extends Model
{
    use HasFactory;


    /**
     * Timestamp recording
     *
     * @var bool
     */
    public $timestamps = false;
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
    protected $table = 'password_resets';
    /**
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'phone',
        'email',
        'token',
        'created_at',
        'expired_at',
        'reset_via',
    ];

    /**
     * Function that will be run whenever event happened
     *
     * @return  void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(PasswordResetObserver::class);
    }

    /**
     * Define the user relation
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
