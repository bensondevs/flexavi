<?php

namespace App\Models\Inspection;

use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Observers\InspectionObserver;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Znck\Eloquent\Traits\BelongsToThrough;


class Inspection extends Model
{

    use HasFactory;

    use SoftDeletes;
    use BelongsToThrough;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'inspections';

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
     * A collection of inspection questions
     * By these questions,
     * it's decideable to generate the condition of the inspection
     *
     * @var array
     */
    const QUESTIONS = [
        // First phase question
        [
            [
                'question' => 'Does the customer’s roof had a leaking problem?',
                'choices' => ['yes', 'no'],
                'options' => [],
            ],
            [
                'question' => 'Is the complete roof outdated?',
                'choices' => ['yes', 'no'],
                'options' => [],
            ],
            [
                'question' => 'Which parts needs to be fixed?',
                'choices' => [],
                'options' => [
                    'Roof strip incl container',
                    'Roof strip excl container',
                    'Roof boarding',
                    'Isolation',
                    'Roof trim',
                    'Mastic edges',
                    'Water drainage',
                    'Gutter',
                    'Battens and counter battens',
                    'Fastdeck roof tiles',
                    'OVH Rooftiles',
                    'Bitumen top layer',
                    'Bitumen bottom layer',
                ],
            ],
        ],

        // Second phase question
        [
            [
                'question' => 'Does the customer wants a roof renovation?',
                'choices' => ['yes', 'no'],
                'options' => [],
            ],
        ],

        // Third phase question
        [
            [
                'question' => 'Is a local repair needed?',
                'choices' => ['yes', 'no'],
                'options' => [
                    'Rainwater flow + amount',
                    'Air underneath top layer',
                    'Condensation',
                    'Ripped roofing',
                    'Change roofing tiles + deliver',
                    'Local repair outdated roofing felt',
                    'Flat roof coating',
                ],
            ],
        ],

        // Fourth phase question
        [
            [
                'question' => 'Should there be a renewal?',
                'choices' => ['yes', 'no'],
                'options' => [],
            ],
        ],
    ];

    /**
     * Set which columns are searchable
     *
     * @var array
     */
    public $searchableFields = [];

    protected $fillable = [
        'company_id',
        'appointment_id'
    ];

    /**
     * Define the castable attributes
     *
     * @var array
     */
    protected $casts = [
        'inspection_answers' => 'array',
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(InspectionObserver::class);
    }

    /**
     * Define the searchable query
     *
     * @param Builder $query
     * @return Builder
     */
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['company', 'appointment']);
    }

    /**
     * Define the appointment relation
     *
     * @return BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Define the inspector relation
     *
     * @return HasMany
     */
    public function inspectors()
    {
        return $this->hasMany(Inspector::class);
    }

    /**
     * Define the inspector relation
     *
     * @return HasMany
     */
    public function pictures()
    {
        return $this->hasMany(InspectionPicture::class, 'inspection_id', 'id');
    }

    /**
     * Define the company relation
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Define the company relation
     *
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsToThrough(
            Customer::class,
            [Appointment::class],
            'appointment_id'
        );
    }
}
