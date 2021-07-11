<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Inspection extends Model
{
    use SoftDeletes;

    protected $table = 'inspections';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

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