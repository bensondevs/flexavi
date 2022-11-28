<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\Log\Log;
use App\Models\Owner\Owner;

class LogController extends Controller
{
    public function allActionMessages()
    {
        $rawLogMessages = \Lang::get("logs");

        $models = collect($rawLogMessages)->map(function ($log, $logModule) {
            try {
                $modelClass = "App\\Models\\" . ucfirst(str_camel_case($logModule));
                $model = $modelClass::inRandomOrder()->first() ?? $modelClass::factory()->create();

                return $model;
            } catch (\Exception $e) {
                $modelClass = "App\\Models\\" . ucfirst(str_camel_case($logModule));
                $model = $modelClass::factory()
                    ->createOneQuietly(["id" => generateUuid()]);

                return $model;
            }
        });
        $logableModules = $models->map(fn ($model) => get_class($model));

        $causers = (function () {
            $employees = Employee::with("user", "company")->whereHas("user")->whereHas("company")
                ->limit(5)->orderBy("created_at", "ASC")->get();
            $owners = Owner::with("user", "company")->whereHas("user")->whereHas("company")
                ->limit(5)->orderBy("created_at", "ASC")->get();

            return collect($employees)->merge($owners);
        })();

        $rawLogMessagesArrayDot = \Arr::dot($rawLogMessages);
        $logs = collect($rawLogMessagesArrayDot)
            ->map(function ($logMessage, $logName) use ($models, $causers) {
                $causer = $causers->random();

                $subjectClass = "App\\Models\\" . ucfirst(str_camel_case(\Str::before($logName, ".")));
                $subject = $models->whereInstanceOf($subjectClass)->first();

                return new Log([
                    "id" => generateUuid(),
                    "company_id" => $causer->company->id,
                    "log_name" => $logName,
                    "description" => null,
                    "properties" => [
                        "old" => [
                            "subject" => $subject
                        ]
                    ],
                    "subject_id" => $subject->id,
                    "subject_type" => $subjectClass,
                    "causer_id" => $causer->user->id,
                    "causer_type" => get_class($causer->user),
                ]);
            });

        $logs = $logs->pluck("message", "log_name");

        return response()->json([
            "logable_modules" => $logableModules,
            "raw_log_action_messages" => $rawLogMessagesArrayDot,
            "raw_log_action_messages_count" => count($rawLogMessagesArrayDot),
            "formatted_log_action_messages" => $logs,
            "formatted_log_action_messages_count" => $logs->count(),
        ]);
    }
}
