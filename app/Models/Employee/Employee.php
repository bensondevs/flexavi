<?php

namespace App\Models\Employee;

use App\Enums\Employee\{EmployeeType as Type, EmploymentStatus as Status};
use App\Models\Address\Address;
use App\Models\Appointment\Appointment;
use App\Models\Appointment\AppointmentEmployee;
use App\Models\Company\Company;
use App\Models\Inspection\Inspection;
use App\Models\Inspection\Inspector;
use App\Models\User\User;
use App\Models\Workday\Workday;
use App\Models\Worklist\Worklist;
use App\Models\Worklist\WorklistEmployee;
use App\Observers\EmployeeObserver as Observer;
use App\Rules\Helpers\Media;
use App\Traits\ModelMutators;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasManyThrough, MorphOne};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Znck\Eloquent\Traits\BelongsToThrough;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasRelationships;
    use BelongsToThrough;
    use Searchable;
    use ModelMutators;

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
    public array $searchableFields = ['title'];

    /**
     * Set which relations are searchable
     *
     * @var array
     */
    public array $searchableRelations = [
        'user' => ['fullname', 'phone', 'email'],
    ];

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
        'contract_file_path',
    ];

    /**
     * Get array of possible employment types.
     *
     * @return array
     */
    public static function collectAllTypes(): array
    {
        return Type::asSelectArray();
    }

    /**
     * Get array of possible employee statuses.
     *
     * @return array
     */
    public static function collectAllEmploymentStatus(): array
    {
        return Status::asSelectArray();
    }

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param Builder $query
     * @return Builder
     */
    public function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with('user');
    }

    /**
     * Add query only populate employees with Administrative type
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAdministrative(Builder $query): Builder
    {
        return $query->where('employee_type', Type::Administrative);
    }

    /**
     * Add query only populate employees with Roofer type
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRoofer(Builder $query): Builder
    {
        return $query->where('employee_type', Type::Roofer);
    }

    /**
     * Get the employee user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the  address attached to employee
     *
     * @return MorphOne
     */
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Get the company of the employee
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the employee inspections
     *
     * @return BelongsToMany
     */
    public function inspections()
    {
        return $this->belongsToMany(Inspection::class, Inspector::class);
    }

    /**
     * Get the all quotations attached to employee.appointments
     *
     * @return HasManyDeep
     */
    public function quotations(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->appointments(),
            (new Appointment())->quotation()
        );
    }

    /**
     * Get the all appointments attached to employee
     *
     * @return HasManyThrough
     */
    public function appointments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Appointment::class,
            AppointmentEmployee::class,
            'user_id',
            'id',
            'user_id',
            'appointment_id'
        );
    }

    /**
     * Get the all warranties attached to employee.appointments
     *
     * @return HasManyDeep
     */
    public function warranties(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->appointments(),
            (new Appointment())->warranties()
        );
    }

    /**
     * Get avg worklists cost
     *
     * @return float|int
     */
    public function getAverageWorklistsCostsAttribute(): float|int
    {
        return $this->worklistsCosts()->avg("amount") ?: 0;
    }

    /**
     * Get worklists costs
     *
     * @return HasManyDeep
     */
    public function worklistsCosts(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->worklists(),
            (new Worklist())->costs()
        );
    }

    /**
     * Get attached worklists of the employee
     *
     * @return HasManyThrough
     */
    public function worklists(): HasManyThrough
    {
        return $this->hasManyThrough(
            Worklist::class,
            WorklistEmployee::class,
            'user_id',
            'id',
            'user_id',
            'worklist_id'
        );
    }

    /**
     * Get avg worklists revenue
     *
     * @return float
     */
    public function getAverageWorklistsRevenuesAttribute(): float|int
    {
        return $this->worklistsRevenues()->avg("amount") ?: 0;
    }

    /**
     * Get worklists Revenues
     *
     * @return HasManyDeep
     */
    public function worklistsRevenues(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->worklists(),
            (new Worklist())->revenues()
        );
    }

    /**
     * Get avg worklists profit
     *
     * @return float
     */
    public function getAverageWorklistsProfitsAttribute(): float|int
    {
        $avgCosts = $this->worklistsCosts()->avg("amount") ?: 0;
        $avgRevenues = $this->worklistsRevenues()->avg("amount") ?: 0;
        return $avgRevenues - $avgCosts;
    }

    /**
     * Get today appointments
     *
     * @return Builder|HasManyDeep
     *
     */
    public function todayAppointments(): Builder|HasManyDeep
    {
        $currentWorkday = Workday::where(
            'date',
            today()->toDateString()
        )->first();

        return $this->hasManyDeepFromRelations($this->appointments())->whereHas(
            'appointmentables',
            function ($query) use ($currentWorkday) {
                $query->where('appointmentable_type', 'App\\Models\\Workday\\Workday');
                $query->where(
                    'appointmentable_id',
                    $currentWorkday ? $currentWorkday->id : 0
                );
            }
        );
    }

    /**
     * Get the employment type description.
     *
     * @return string
     */
    public function getEmployeeTypeDescriptionAttribute(): string
    {
        $type = (int) $this->employee_type;

        return Type::getDescription($type);
    }

    /**
     * Get the employee's status description.
     *
     * @return string
     */
    public function getEmploymentStatusDescriptionAttribute(): string
    {
        $status = (int) $this->employment_status;

        return Status::getDescription($status);
    }

    /**
     * Perform download action of the contract file
     *
     * @return StreamedResponse
     * @throws HttpException
     */
    public function downloadContractFile(): StreamedResponse
    {
        if (
            Storage::missing(
                "employees/$this->contract_file_path"
            )
        ) {
            abort(404);
        }

        return Storage::download(
            "employees/$this->contract_file_path"
        );
    }

    /**
     * Create settable attribute of "contract_file"
     * This settable attribute will set the "contract_file_path" and upload
     * the file to the storage
     *
     * @param UploadedFile $file
     * @return void
     */
    public function setContractFileAttribute(UploadedFile $file): void
    {
        $filename = Media::randomFilename($file);
        Storage::putFileAs('employees', $file, $filename);
        $this->attributes['contract_file_path'] = $filename;
    }

    /**
     * Create callable attribute of "contract_file_url"
     * This callable attribute will generate url from the file path
     *
     * @return string|null
     */
    public function getContractFileUrlAttribute(): ?string
    {
        return tryIsset(function () {
            $path = "employees/" . $this->attributes["contract_file_path"];
            return Storage::disk('public')->missing($path)
                ? null
                : Storage::disk('public')->url($path);
        });
    }
}
