<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\StorageFile;

class QuotationAttachment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    protected $table = 'quotation_attachments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'quotation_id',
        'name',
        'description',
        'attachment_path',
    ];

    protected $searchable = [
        'attachment_path',
    ];

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

    public function setAttachmentFileAttribute($attachmentFile)
    {
        $directory = 'uploads/quotations/attachments/';
        $attachment = uploadFile($attachmentFile, $directory);

        return $this->attributes['attachment_path'] = $attachment->path;
    }

    public function getAttachmentFileAttribute()
    {
        $path = $this->attributes['attachment_path'];
        $file = StorageFile::findByPath($path);
        return $file->getFileContent();
    }

    public function getAttachmentUrlAttribute()
    {
        $path = $this->attributes['attachment_path'];
        if (! $file = StorageFile::findByPath($path)) {
            return null;
        }

        return $file->getDownloadUrl();
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}