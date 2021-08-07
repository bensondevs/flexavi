<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Models\Inspection;

class WorkContract extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'work_contracts';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    const CONTRACT_STATUSES = [
        [
            'label' => 'Created',
            'value' => 'created',
        ],
        [
            'label' => 'Send',
            'value' => 'send',
        ],
        [
            'label' => 'Signed',
            'value' => 'signed',
        ]
    ];

    protected $fillable = [
        'company_id',

        'customer_id',
        
        'contract_date_start',
        'contract_date_end',
        'include_weekend',

        'amount',
        'payment_method',
        'status',

        'is_signed',
        'content',
        'pdf_url',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($workContract) {
            $workContract->id = Uuid::generate()->string;
    	});
    }

    public function setPdfUrlAttribute($fileRequest)
    {
        $path = 'storage/uploads/work_contracts/pdfs';
        $uploadedFileName = uploadFile($fileRequest, $path);
        $fileUrl = asset($uploadedFileName);
        
        return $this->attributes['pdf_url'] = $fileUrl;   
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function warranty()
    {
        return $this->hasOne(Warranty::class);
    }

    public function countAmount()
    {
        $total = db('works')
            ->where('quotation_id', $this->attributes['id'])
            ->sum('works.total_price');
        $this->attributes['amount'] = $total;
    }
}