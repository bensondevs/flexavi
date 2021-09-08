<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\Warranty\WarrantyStatus;

class Warranty extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'warranties';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'problem_description',
        'fixing_description',
        'internal_note',
        'customer_note',
    ];

    protected $fillable = [
        'company_id',
        'appointment_id',
        'work_id',
        'status',
        'problem_description',
        'fixing_description',
        'internal_note',
        'customer_note',
        'amount',
        'paid_amount',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($warranty) {
            $warranty->id = Uuid::generate()->string;
    	});
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}