<?php

namespace App\Models\Inspection;

use App\Models\BelongsTo;
use App\Models\MorphToMany;
use App\Models\Work\Work;
use App\Observers\InspectionPictureObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\{HasMedia, InteractsWithMedia};

class InspectionPicture extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'inspection_pictures';

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
     * Set which columns are searchable
     *
     * @var array
     */
    public $searchableFields = [];

    /**
     * Set autoload work
     *
     * @var array
     */
    protected $with = [
        'works.workService'
    ];

    protected $fillable = [
        'inspection_id',
        'name',
        'length',
        'width',
        'amount',
        'note',
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(InspectionPictureObserver::class);
    }

    /**
     * Define the inspection relation
     *
     * @return BelongsTo
     */
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    /**
     * Create callable "works" attribute and get
     * quoted works model data
     *
     * @return MorphToMany
     */
    public function works()
    {
        return $this->morphToMany(Work::class, 'workable');
    }
}
