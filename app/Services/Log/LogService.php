<?php

namespace App\Services\Log;

use App\Models\Log\Log;
use App\Models\User\User;
use App\Services\Template\Log\LogTemplateService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{Arr};
use PHPStan\DependencyInjection\ParameterNotFoundException;
use ReflectionException;

class LogService
{
    /**
     * Log Instance
     *
     * @var Log
     */
    private Log $log;

    /**
     * Instantiate log service
     *
     * @param array $log
     * @return void
     */
    private function __construct(array $log)
    {
        $this->log = new Log($log);
    }

    /**
     * Make a new log
     *
     * @param string $logName
     * @return static
     */
    public static function make(string $logName): static
    {
        $templateService = (new LogTemplateService())
            ->setContent(trans('logs.$logName'));
        $requiredParameters = [];
        foreach ($templateService->getRequiredParameters() as $requiredParameter) {
            $requiredParameters[] = $requiredParameter;
        }
        return new static([
            'log_name' => $logName,
            'required_parameters' => $requiredParameters
        ]);
    }

    /**
     * Format Log message to client preferences language
     *
     * @param Log $log
     * @return string
     */
    public static function formatMessageWithTemplatingService(Log $log): string
    {
        $logName = 'logs.{$log->log_name}';
        $service = new LogTemplateService($log);
        try {
            $parsedParameters = [];
            if (!is_null($log->parameter_values)) {
                foreach ($log->required_parameters as $requiredParameter) {
                    $parsedParameters[$requiredParameter] = $log->parameter_values[$requiredParameter];
                }
            }
            return $service->initialize(
                ...$parsedParameters
            )->setContent(trans($logName))->execute()->render();
        } catch (ParameterNotFoundException|ReflectionException $e) {
            return 'Failed to render log. ' . $e->getMessage();
        }
    }


    /**
     * Write log to database
     *
     * @param array $extras
     * @return Log|null
     */
    public function write(array $extras = []): ?Log
    {
        $log = $this->getLog();
        if (!empty($extras)) {
            $log->fill($extras);
        }

        if (!$log->hasCauser() and auth()->check()) {
            $log->causer_type = User::class;
            $log->causer_id = auth()->user()->id;
        }

        $log->save();

        return $log;
    }

    /**
     *  Get Log Instance
     *
     * @return Log
     */
    public function getLog(): Log
    {
        return $this->log;
    }

    /**
     *  Define the log caused by
     *
     * @param object $causer
     * @return static
     */
    public function by(object $causer): static
    {
        $log = $this->getLog();
        $this->log = $log->fill([
            'causer_type' => get_class($causer),
            'causer_id' => $causer->id,
        ]);

        return $this;
    }

    /**
     *  Define the Log performed on
     *
     * @param Model $subject
     * @return static
     */
    public function on(Model $subject): static
    {
        $log = $this->getLog();
        $this->log = $log->fill([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
        ]);

        return $this;
    }

    /**
     *  Add Property to Log with dot notation array
     *
     * @param string $keyInDotNotation
     * @param mixed $value
     * @return static
     */
    public function with(string $keyInDotNotation, mixed $value): static
    {
        $log = $this->getLog();

        $props = [$keyInDotNotation => $value];
        $props = Arr::undot($props);

        $log->properties = array_merge($log->properties ?? [], $props);
        $this->log = $log;

        return $this;
    }
}
