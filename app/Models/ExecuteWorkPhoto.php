<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType;
use App\Models\StorageFile;
use App\Observers\ExecuteWorkPhotoObserver;

class ExecuteWorkPhoto extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

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
    protected $searchable = [
        'photo_path',
        'photo_description',    
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'execute_work_id',
        'photo_condition_type',
        'photo_path',
        'photo_description',
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

    	self::creating(function ($executeWorkPhoto) {
            $executeWorkPhoto->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create settable attribute of "photo"
     * This settable attribute will save the uploaded photo file
     * to storage directory and record the path to "photo_path" column
     * 
     * @param mixed
     * @return void
     */
    public function setPhotoAttribute($photoFile)
    {
        $directory = 'uploads/execute_works/';
        $photo = uploadFile($photoFile, $directory);

        $this->attributes['photo_path'] = $photo->path;
    }

    /**
     * Create callable attribute of "photo_url"
     * This callable attribute will convert "photo_path" value to
     * accessable url to load image in front-end
     * 
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        $path = $this->attributes['photo_path'];
        if (! $file = StorageFile::findByPath($path)) {
            return null;
        }

        return $file->getDownloadUrl();
    }

    /**
     * Create callable attribute of "photo_condition_type_description"
     * This callable attribute will return type description of 
     * the photo, whether it's before work photo or after work photo.
     * 
     * @return string
     */
    public function getPhotoConditionTypeDescriptionAttribute()
    {
        $type = $this->attributes['photo_condition_type'];
        return PhotoConditionType::getDescription($type);
    }

    /**
     * Collect all possible photo condition types as array
     * 
     * @return array
     */
    public static function collectAllPhotoConditionTypes()
    {
        return PhotoConditionType::asSelectArray();
    }

    /**
     * Get execute work of the current photo
     */
    public function executeWork()
    {
        return $this->belongsTo(ExecuteWork::class);
    }
}