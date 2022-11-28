<?php

namespace App\Models\Workday;

use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

class CompanyWorkday extends Model
{

    use HasFactory;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'company_workdays';

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
    protected $fillable = ['company_id', 'schedules_json', 'include_weekend'];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($companyWorkday) {
            $companyWorkday->id = Uuid::generate()->string;
        });
    }

    /**
     * Define the searchable query
     *
     * @param Builder $query
     * @return Builder
     */
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['company']);
    }

    /**
     * Create settable attribute of "schedule"
     * This settable attribute will set the schedule using array
     * to database column of "schedules_json" and store it as json in database
     *
     * @param array  $value
     * @return void
     */
    public function setScheduleAttribute(array $value)
    {
        $schedule = [
            'sunday' => json_encode($value['sunday']),
            'monday' => json_encode($value['monday']),
            'tuesday' => json_encode($value['tuesday']),
            'wednesday' => json_encode($value['wednesday']),
            'thursday' => json_encode($value['thursday']),
            'friday' => json_encode($value['friday']),
            'saturday' => json_encode($value['saturday']),
        ];

        $this->attributes['schedules_json'] = json_encode($schedule);
    }

    /**
     * Get callable attribute of "schedule"
     * This callable attribute will return array from stored json
     * from database column of "schedules_json"
     *
     * @return array
     */
    public function getScheduleAttribute()
    {
        $schedules = json_decode($this->attributes['schedules_json'], true);
        foreach ($schedules as $dayName => $schedule) {
            $schedules[$dayName] = json_decode($schedule, true);
        }

        return $schedules;
    }
}
