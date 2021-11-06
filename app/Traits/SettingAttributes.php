<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SettingAttributes 
{
    public function scopeVatPercentage(Builder $query)
    {
        return $query->where('key', 'vat_percentage')->first();
    }

    public function scopeNotificationEnability(Builder $query)
    {
        return $query->where('key', 'notification_enability')->first();
    }

    public function scopeStartWorkingTime(Builder $query)
    {
        return $query->where('key', 'start_working_time')->first();
    }

    public function scopeEndWorkingTime(Builder $query)
    {
        return $query->where('key', 'end_working_time')->first();
    }

    public function scopeDefaultWorklistPerWorkday(Builder $query)
    {
        return $query->where('key', 'default_worklist_per_workday')->first();
    }
}