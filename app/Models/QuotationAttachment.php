<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Models\StorageFile;

class QuotationAttachment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'quotation_attachments';

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
        'attachment_path',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'quotation_id',
        'name',
        'description',
        'attachment_path',
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

    	self::creating(function ($attachment) {
            $attachment->id = Uuid::generate()->string;
    	});

        self::deleting(function ($attachment) {
            deleteFile($attachment->attachment_path);
        });
    }

    /**
     * Create settable "attachment_file" attribute
     * This settable attribute will allow file upload and store path to
     * column of "attachment_path"
     * 
     * @param mixed  $attachmentFile
     * @return void
     */
    public function setAttachmentFileAttribute($attachmentFile)
    {
        $directory = 'uploads/quotations/attachments/';
        $attachment = uploadFile($attachmentFile, $directory);

        return $this->attributes['attachment_path'] = $attachment->path;
    }

    /**
     * Create callable "attachment_file" attribute
     * This callable attribute will allow getting file upload as content
     * 
     * @return UploadedFile
     */
    public function getAttachmentFileAttribute()
    {
        $path = $this->attributes['attachment_path'];
        $file = StorageFile::findByPath($path);
        return $file->getFileContent();
    }

    /**
     * Create callable "attachment_url" attribute
     * This callable attribute will allow getting url of the file
     * 
     * @return string
     */
    public function getAttachmentUrlAttribute()
    {
        $path = $this->attributes['attachment_path'];
        if (! $file = StorageFile::findByPath($path)) {
            return null;
        }

        return $file->getDownloadUrl();
    }

    /**
     * Get quotation attachment company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get parent quotation of this attachment
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}