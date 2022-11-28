<?php

namespace App\Models\Setting;

use App\Enums\Invoice\InvoiceReminderSentType;
use App\Observers\InvoiceSettingObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceSetting extends Model
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
    protected $table = "invoice_settings";

    /**
     * The primary key of the model
     *
     * @var string
     */
    protected $primaryKey = "id";


    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'auto_reminder_activated',
        'first_reminder_type',
        'first_reminder_days',
        'second_reminder_type',
        'second_reminder_days',
        'third_reminder_type',
        'third_reminder_days',
        'debt_collector_reminder_type',
        'debt_collector_reminder_days',
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
        self::observe(InvoiceSettingObserver::class);
    }

    /**
     * Get the invoice that owns the InvoiceSetting
     *
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get First reminder type description
     *
     * @return Attribute
     */
    public function firstReminderTypeDescription(): Attribute
    {
        return Attribute::make(
            get: fn($value) => InvoiceReminderSentType::getDescription($this->attributes['first_reminder_type']),
        );
    }

    /**
     * Get second reminder type description
     *
     * @return Attribute
     */
    public function secondReminderTypeDescription(): Attribute
    {
        return Attribute::make(
            get: fn($value) => InvoiceReminderSentType::getDescription($this->attributes['second_reminder_type']),
        );
    }

    /**
     * Get third reminder type description
     *
     * @return Attribute
     */
    public function thirdReminderTypeDescription(): Attribute
    {
        return Attribute::make(
            get: fn($value) => InvoiceReminderSentType::getDescription($this->attributes['second_reminder_type']),
        );
    }

    /**
     * Get debt reminder type description
     *
     * @return Attribute
     */
    public function debtCollectorReminderTypeDescription(): Attribute
    {
        return Attribute::make(
            get: fn($value) => InvoiceReminderSentType::getDescription($this->attributes['debt_collector_reminder_type']),
        );
    }

    /**
     *
     * Get auto reminder activated
     * @return Attribute
     */
    public function autoReminderActivated(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (bool)$this->attributes['auto_reminder_activated']
        );
    }
}
