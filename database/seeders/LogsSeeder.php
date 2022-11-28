<?php

namespace Database\Seeders;

use App\Models\{Log\Log, User\User};
use App\Models\Company\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\{Arr, Str};
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

class LogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $iterator = new \DirectoryIterator(app_path('Models'));
        $subFolders = [];
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $subFolders[] = $fileinfo->getFileName();
            }
        }
        $rawLogs = [];

        $companies = Company::with([
            'owners' => fn($q) => $q->limit(25),
            'employees' => fn($q) => $q->limit(10),
            'customers' => fn($q) => $q->limit(10),
        ])->get();

        $owners = $companies->map(fn($company) => $company->owners->toArray())->toArray();
        $userables = array_merge($owners, []);
        $logNames = $this->logNames();

        foreach ($userables as $userable) {
            if (isset($userable["company"]) && isset($userable["user"])) {
                $user = $userable["user"];
                $company = $userable["company"];

                shuffle_assoc($logNames);
                foreach (array_slice($logNames, 0, 5) as $logNameKey => $logName) {
                    $className = ucfirst(str_camel_case(Str::before($logNameKey, ".")));
                    foreach ($subFolders as $subFolder) {
                        if (File::exists((app_path('Models/' . $subFolder . '/' . $className . '.php')))) {
                            $subjectClass = "App\\Models\\" . $subFolder . "\\$className";
                            $subject = $subjectClass::inRandomOrder()->first();
                        }
                    }

                    if ($subject) {
                        $props = [
                            "old" => [
                                "subject" => $subject
                            ],
                        ];

                        $createdAt = now()->subDays(rand(2, 10))->subHours(rand(0, 24))->subMinutes(rand(30, 60));
                        if (array_key_exists('is_prime_owner', $userable)) {
                            $requiredParameters = ['causerId', 'ownerId'];
                            $parameterValues = [
                                'causerId' => $userable['user_id'],
                                'ownerId' => $userable['id'],
                            ];
                        } else {
                            $requiredParameters = ['causerId', 'employeeId'];
                            $parameterValues = [
                                'causerId' => $userable['user_id'],
                                'employeeId' => $userable['id'],
                            ];
                        }
                        $rawLogs[] = [
                            'id' => generateUuid(),
                            'company_id' => $company["id"],
                            'log_name' => $logNameKey,
                            'properties' => json_encode($props),
                            'subject_id' => $subject["id"],
                            'subject_type' => $subjectClass,
                            'causer_id' => $user["id"],
                            'required_parameters' => json_encode($requiredParameters),
                            'parameter_values' => json_encode($parameterValues),
                            'causer_type' => User::class,
                            'created_at' => $createdAt,
                            'deleted_at' => rand(0, 1) ? null : $createdAt->copy()
                                ->addDays(rand(0, 1))->addHours(rand(0, 24))->addMinutes(rand(30, 60)),
                        ];
                    }
                }
            }
        }

        foreach (array_chunk($rawLogs, 100) as $chunk) {
            Log::insert($chunk);
        }
        Log::whereNull('required_parameters')->forceDelete();
    }

    /**
     * Get all log names
     *
     * @return array
     */
    private function logNames(): array
    {
        $logs = Lang::get("logs");
        return Arr::dot($logs);
    }
}
