<?php

namespace App\Models\Log;

use App\Models\Company\Company;
use App\Services\Log\LogService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Webpatser\Uuid\Uuid;

class Log extends SpatieActivity
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
     * Table name
     *
     * @var string
     */
    protected $table = 'logs';

    /**
     * Table name primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Always auto appended attributes
     *
     * @var array
     */
    protected $appends = ['message'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'required_parameters' => 'array',
        'parameter_values' => 'array',
        'properties' => 'array',
    ];

    /**
     * list of fillables
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'company_id',
        'log_name',
        'description',
        'properties',
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'required_parameters',
        'parameter_values',
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
        self::creating(function ($log) {
            $parameterValues = [];
            if (is_null($log->id)) {
                $log->id = Uuid::generate()->string;
            }

            $roleModel = auth()->user()->role_model ?? null;
            if (!is_null($roleModel)) {
                $log->company_id = $roleModel->company_id;
            }

            foreach ($log->required_parameters as $requiredParameter) {
                $value = $requiredParameter === 'causerId' ?
                    $log->causer_id : $log->subject_id;
                $parameterValues[$requiredParameter] = $value;
            }
            $log->parameter_values = $parameterValues;
        });
    }

    /**
     * Return the log message with user preferences language
     *
     * @return string
     */
    public function getMessageAttribute(): string
    {
        return LogService::formatMessageWithTemplatingService($this);
    }

    /**
     * Check whether current log model has causer set into the value.
     *
     * @return bool
     */
    public function hasCauser(): bool
    {
        return isset($this->causer_type) and isset($this->causer_id);
    }

    /**
     * Get the company of the log
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
