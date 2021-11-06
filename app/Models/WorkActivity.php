<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkActivity extends Model
{
    use HasFactory;

    protected $table = 'work_activities';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'activity_name',
    ];

    protected $fillable = [
        'company_id',
        'assignable_type',
        'assignable_id',
        'activity_name',
        'price',
        'unit',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($workActivity) {
            $workActivity->id = Uuid::generate()->string;
    	});
    }
}