<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspection extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

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
            ]
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
            ]
        ],

        // Fourth phase question
        [
            [
                'question' => 'Should there be a renewal?',
                'choices' => ['yes', 'no'],
                'options' => [],
            ]
        ],
    ];

    /**
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        'sidenote',
    ];

    protected $fillable = [
        'company_id',

        'signable_type',
        'signable_id',

        'is_signed',
        
        'sidenote',
    ];

    protected $casts = [
        'inspection_answers' => 'array',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($inspection) {
            $inspection->id = Uuid::generate()->string;
    	});
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function inspectors()
    {
        return $this->hasMany(Inspector::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}