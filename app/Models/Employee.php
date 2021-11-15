<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Znck\Eloquent\Traits\BelongsToThrough;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;

use App\Enums\Employee\{
    EmployeeType as Type, 
    EmploymentStatus as Status
};

class Employee extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;
    use HasRelationships;
    use BelongsToThrough;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'employees';

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
        'title',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'employee_type',
        'employment_status',
    ];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($employee) {
            $employee->id = Uuid::generate()->string;
    	});
    }

    /**
     * Add query only populate employees with Administrative type
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeAdministrative(Builder $query)
    {
        return $query->where('employee_type', Type::Administrative);
    }
    
    /**
     * Add query only populate employees with Roofer type
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeRoofer(Builder $query)
    {
        return $query->where('employee_type', Type::Roofer);
    }

    /**
     * Get the employee user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the all addresses attached to employee
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get the company of the employee
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the employee inspections
     */
    public function inspections()
    {
        return $this->belongsToMany(Inspection::class, Inspector::class);
    }

    /**
     * Get the all appointments attached to employee
     */
    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, AppointmentEmployee::class);
    }

    /**
     * Get the employement type description.
     *
     * @return string
     */
    public function getEmployeeTypeDescriptionAttribute()
    {
        $type = $this->attributes['employee_type'];
        return Type::getDescription($type);
    }

    /**
     * Get the employee's status description.
     *
     * @return string
     */
    public function getEmploymentStatusDescriptionAttribute()
    {
        $status = $this->attributes['employment_status'];
        return Status::getDescription($status);
    }

    /**
     * Get array of possible employment types.
     *
     * @return array
     */
    public static function collectAllTypes()
    {
        return Type::asSelectArray();
    }

    /**
     * Get array of possible employee statuses.
     *
     * @return array
     */
    public static function collectAllEmploymentStatus()
    {
        return Status::asSelectArray();
    }
}