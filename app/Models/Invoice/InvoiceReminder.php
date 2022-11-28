<?php

namespace App\Models\Invoice;

use App\Enums\Invoice\InvoiceReminderSentType;
use App\Observers\InvoiceReminderObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceReminder extends Model
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
     * Set column cast
     *
     * @var string[]
     */
    public $casts = [
        'first_reminder_at' => 'date',
        'first_reminder_sent_at' => 'datetime',
        'second_reminder_at' => 'date',
        'second_reminder_sent_at' => 'datetime',
        'third_reminder_at' => 'date',
        'third_reminder_sent_at' => 'datetime',
        'sent_to_debt_collector_at' => 'date',
        'paid_via_debt_collector_at' => 'datetime',
    ];
    /**
     * The table name
     *
     * @var string
     */
    protected $table = "invoice_reminders";
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

        'first_reminder_sent_type',
        'first_reminder_at',
        'user_first_reminder_sent_at',
        'customer_first_reminder_sent_at',

        'second_reminder_sent_type',
        'second_reminder_at',
        'user_second_reminder_sent_at',
        'customer_second_reminder_sent_at',

        'third_reminder_sent_type',
        'third_reminder_at',
        'user_third_reminder_sent_at',
        'customer_third_reminder_sent_at',

        'debt_collector_reminder_sent_type',
        'sent_to_debt_collector_at',
        'user_sent_to_debt_collector_sent_at',
        'customer_sent_to_debt_collector_sent_at',

        'paid_via_debt_collector_at'
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
        self::observe(InvoiceReminderObserver::class);
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
     * create callable `first_reminder_send_type_description` attribute
     * this attribute will return the description of the first_reminder_send_type
     *
     * @return string
     */
    public function getFirstReminderSentTypeDescriptionAttribute(): string
    {
        return InvoiceReminderSentType::getDescription($this->first_reminder_sent_type);
    }

    /**
     * create callable `second_reminder_send_type_description` attribute
     * this attribute will return the description of the second_reminder_send_type
     *
     * @return string
     */
    public function getSecondReminderSentTypeDescriptionAttribute(): string
    {
        return InvoiceReminderSentType::getDescription($this->second_reminder_sent_type);
    }

    /**
     * create callable `third_reminder_send_type_description` attribute
     * this attribute will return the description of the third_reminder_send_type
     *
     * @return string
     */
    public function getThirdReminderSentTypeDescriptionAttribute(): string
    {
        return InvoiceReminderSentType::getDescription($this->third_reminder_sent_type);
    }

    /**
     * create callable `third_reminder_send_type_description` attribute
     * this attribute will return the description of the third_reminder_send_type
     *
     * @return string
     */
    public function getDebtCollectorReminderSentTypeDescriptionAttribute(): string
    {
        return InvoiceReminderSentType::getDescription($this->third_reminder_sent_type);
    }

    /**
     * Determine if the reminder sent to the customer
     *
     * @return bool
     */
    public function isFirstReminderSendToCustomer(): bool
    {
        return $this->first_reminder_sent_type === InvoiceReminderSentType::InHouseUserWithCustomer;
    }

    /**
     * Determine if the reminder sent to the customer
     *
     * @return bool
     */
    public function isSecondReminderSendToCustomer(): bool
    {
        return $this->second_reminder_sent_type === InvoiceReminderSentType::InHouseUserWithCustomer;
    }

    /**
     * Determine if the reminder sent to the customer
     *
     * @return bool
     */
    public function isThirdReminderSendToCustomer(): bool
    {
        return $this->third_reminder_sent_type === InvoiceReminderSentType::InHouseUserWithCustomer;
    }

    /**
     * Determine if the reminder sent to the customer
     *
     * @return bool
     */
    public function isDebtCollectorReminderSendToCustomer(): bool
    {
        return $this->debt_collector_reminder_sent_type === InvoiceReminderSentType::InHouseUserWithCustomer;
    }


    /**
     * Determine if the reminder can be sent
     *
     * @return bool
     */
    public function isCustomerFirstReminderSendable(): bool
    {
        return $this->customer_first_reminder_sent_at === null;
    }

    /**
     * Determine if the reminder can be sent
     *
     * @return bool
     */
    public function isCustomerSecondReminderSendable(): bool
    {
        return $this->customer_second_reminder_sent_at === null;
    }

    /**
     * Determine if the reminder can be sent
     *
     * @return bool
     */
    public function isCustomerThirdReminderSendable(): bool
    {
        return $this->customer_third_reminder_sent_at === null;
    }

    /**
     * Determine if the reminder can be sent
     *
     * @return bool
     */
    public function isCustomerDebtCollectorReminderSendable(): bool
    {
        return $this->customer_sent_to_debt_collector_sent_at === null;
    }


    /**
     * Determine if the reminder can be sent
     *
     * @return bool
     */
    public function isUserFirstReminderSendable(): bool
    {
        return $this->user_first_reminder_sent_at === null;
    }

    /**
     * Determine if the reminder can be sent
     *
     * @return bool
     */
    public function isUserSecondReminderSendable(): bool
    {
        return $this->user_second_reminder_sent_at === null;
    }

    /**
     * Determine if the reminder can be sent
     *
     * @return bool
     */
    public function isUserThirdReminderSendable(): bool
    {
        return $this->user_third_reminder_sent_at === null;
    }

    /**
     * Determine if the reminder can be sent
     *
     * @return bool
     */
    public function isUserDebtCollectorReminderSendable(): bool
    {
        return $this->user_sent_to_debt_collector_sent_at === null;
    }
}
