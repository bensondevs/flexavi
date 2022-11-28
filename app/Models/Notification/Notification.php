<?php

namespace App\Models\Notification;

use App\Enums\Notification\NotificationType;
use App\Models\Company\Company;
use App\Observers\NotificationObserver;
use App\Services\Notification\NotificationService;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;
    use Searchable;

    /**
     * Define searchable fields
     *
     * @var array
     */
    public array $searchableFields = [];

    /**
     * Define the searchable relations
     *
     * @var array
     */
    public array $searchableRelations = [
        'formattedContent' => [
            'title', 'body', 'message'
        ]
    ];

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
     * The table name
     *
     * @var string
     */
    protected $table = 'notifications';

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
        'company_id',
        'notification_name',
        'notifier_id',
        'notifier_type',
        'actor_type',
        'actor_id',
        'object_type',
        'object_id',
        'type',
        'message',
        'body',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        //
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
        self::observe(NotificationObserver::class);
    }

    /**
     * Get notifiable model attached to this notification
     *
     * @return MorphTo
     */
    public function notifier(): MorphTo
    {
        return $this->morphTo('notifier');
    }

    /**
     * Get actor model attached to this notification
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get actor model attached to this notification
     *
     * @return MorphTo
     */
    public function actor(): MorphTo
    {
        return $this->morphTo('actor');
    }

    /**
     * Get object model attached to this notification
     *
     * @return MorphTo
     */
    public function object(): MorphTo
    {
        return $this->morphTo('object')->withTrashed();
    }

    /**
     * create callable `notification_type` attribute
     * this callable will return the notification type
     *
     * @return string
     */
    public function getTypeDescriptionAttribute(): string
    {
        return NotificationType::getDescription($this->attributes['type']);
    }

    /**
     * create callable `formatted_date` attribute
     * this callable will return created_at date in format `D, d M Y`
     *
     * @return string
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('D, d M Y');
    }

    /**
     * create callable `formatted_time` attribute
     * this callable will return created_at time in format `g:i A`
     * Example: 12:00 AM
     *
     * @return string
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('g:i A');
    }

    /**
     * create callable `formatted_time_in_human` attribute
     * this callable will return created_at time in format for human
     * Example: 12 hours ago
     *
     * @return string
     */
    public function getFormattedTimeInHumanAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Formatting message with the given data
     *
     * @return ?string
     */
    public function getMessageAttribute(): ?string
    {
        return NotificationService::formatMessage($this);
    }

    /**
     * Formatting title with the given data
     *
     * @return ?string
     */
    public function getTitleAttribute(): ?string
    {
        return NotificationService::formatTitle($this);
    }

    /**
     * Formatting body with the given data
     *
     * @return ?string
     */
    public function getBodyAttribute(): ?string
    {
        return NotificationService::formatBody($this);
    }

    /**
     * Formatted content
     *
     * @return HasOne
     */
    public function formattedContent(): HasOne
    {
        return $this->hasOne(NotificationFormattedContent::class)->where('locale', app()->getLocale());
    }
}
