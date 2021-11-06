<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Fidum\EloquentMorphToOne\HasMorphToOne;
use Fidum\EloquentMorphToOne\MorphToOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\Cost\CostableType;

class Cost extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use HasRelationships;
    use HasMorphToOne;

    protected $table = 'costs';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'cost_name',
    ];

    protected $fillable = [
        'company_id',

        'cost_name',
        'amount',
        'paid_amount',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($cost) {
            $cost->id = Uuid::generate()->string;
    	});
    }

    public function getUnpaidAmountAttribute()
    {
        $amount = $this->attributes['amount'];

        if (! $paid = $this->attributes['paid_amount']) {
            return 0;
        }

        return $amount - $paid;
    }

    public function getIsSettledAttribute()
    {
        $unpaid = $this->getUnpaidCostAttribute();
        return $unpaid <= 0;
    }

    public function setReceiptFileAttribute($receiptFile)
    {
        $directory = 'uploads/costs/receipts/';
        $receipt = uploadFile($receiptFile, $directory);

        return $this->attributes['receipt_path'] = $receipt->path;
    }

    public static function collectAllCostableTypes()
    {
        return CostableType::asSelectArray();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function appointments()
    {
        return $this->morphedByMany(Appointment::class, 'costable');
    }

    public function getAppointmentAttribute()
    {
        return $this->appointments->first();
    }

    public function worklists()
    {
        return $this->morphedByMany(Worklist::class, 'costable');
    }

    public function getWorklistAttribute()
    {
        return $this->worklists()->first();
    }

    public function workdays()
    {
        return $this->morphedByMany(Workday::class, 'costable');
    }

    public function getWorkdayAttribute()
    {
        return $this->workdays()->first();
    }

    public function costables()
    {
        return $this->hasMany(Costable::class);
    }

    public function receipt()
    {
        return $this->morphOne(Receipt::class, 'receiptable');
    }
}