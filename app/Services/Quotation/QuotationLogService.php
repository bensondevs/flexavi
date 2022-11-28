<?php

namespace App\Services\Quotation;

use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationLog;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class QuotationLogService
{
    /**
     * QuotationLog Instance
     *
     * @var QuotationLog
     */
    private QuotationLog $quotationLog;

    /**
     * Instantiate log service
     *
     * @param array $quotationLog
     * @return void
     */
    private function __construct(array $quotationLog)
    {
        $this->quotationLog = new QuotationLog($quotationLog);
    }

    /**
     * Make a new log
     *
     * @param string $logName
     * @param Quotation $quotation
     * @return static
     */
    public static function make(string $logName, Quotation $quotation): static
    {
        return new static([
            "log_name" => $logName,
            'quotation_id' => $quotation->id,
        ]);
    }

    /**
     * Format QuotationLog message to client preferences language
     *
     * @param QuotationLog $log
     * @return ?string
     */
    public static function formatMessage(QuotationLog $log): ?string
    {
        $logName = "quotation_logs.$log->log_name";
        if ($log->log_name === trans($logName)) {
            return null;
        }
        $content = trans($logName);
        $actor = $log->actor;
        $properties = json_decode($log->properties);

        $variables = Str::matchAll("/:([A-Za-z0-9_.]+)/", $content);
        if (count($variables) === 0) {
            return $content;
        }

        $replaces = [];
        foreach ($variables->toArray() as $variable) {
            $data = ${Str::before($variable, ".")};
            $key = Str::after($variable, ".");
            if (is_null($data)) return null;

            $replaces[$variable] = arrayobject_accessor($data, $key);

        }
        return trans($logName, $replaces);
    }


    /**
     * Write log to database
     *
     * @return QuotationLog|null
     */
    public function write(): ?QuotationLog
    {
        $log = $this->getQuotationLog();
        $log->save();

        return $log;
    }

    /**
     *  Get QuotationLog Instance
     *
     * @return QuotationLog
     */
    public function getQuotationLog(): QuotationLog
    {
        return $this->quotationLog;
    }

    /**
     *  Define the log caused by
     *
     * @param object $causer
     * @return static
     */
    public function by(object $causer): static
    {
        $log = $this->getQuotationLog();
        $this->quotationLog = $log->fill([
            "actor_type" => get_class($causer),
            "actor_id" => $causer->id,
        ]);

        return $this;
    }

    /**
     *  Add Property to QuotationLog with dot notation array
     *
     * @param string $keyInDotNotation
     * @param mixed $value
     * @return static
     */
    public function with(string $keyInDotNotation, mixed $value): static
    {
        $log = $this->getQuotationLog();

        $props = [$keyInDotNotation => $value];
        $props = Arr::undot($props);

        $log->properties = array_merge($log->properties ?? [], $props);
        $this->quotationLog = $log;

        return $this;
    }
}
