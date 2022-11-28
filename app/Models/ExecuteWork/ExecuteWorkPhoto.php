<?php

namespace App\Models\ExecuteWork;

use App\Models\HasMany;
use App\Observers\ExecuteWorkPhotoObserver;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ExecuteWorkPhoto extends Model implements HasMedia
{

    use HasFactory;

    use SoftDeletes;
    use InteractsWithMedia;



    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'execute_work_photos';

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
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'execute_work_id',
        'note',
        'length',
        'name'
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(ExecuteWorkPhotoObserver::class);
    }

    /**
     * get photos url
     *
     * @return array
     */
    public function getPhotosUrl()
    {
        $photos = [];
        foreach ($this->getMedia('execute_work_photos') as $media) {
            $photos[] = $media->getFullUrl();
        }
        return $photos;
    }

    /**
     * Create callable "works" attribute and get
     * quoted works model data
     *
     * @return HasMany
     */
    public function works()
    {
        return $this->hasMany(WorkWarranty::class);
    }

    /**
     * Get execute work of the current photo
     *
     * @return BelongsTo
     */
    public function executeWork()
    {
        return $this->belongsTo(ExecuteWork::class);
    }
}
