<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspector extends Model
{
    use HasFactory;

    protected $table = 'inspectors';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'inspection_id',
        'employee_id',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($inspector) {
            $inspector->id = Uuid::generate()->string;
    	});
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}