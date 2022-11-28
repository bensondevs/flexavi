<?php

namespace App\Http\Requests\Company\Logs;

use App\Models\Log\Log;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class FindLogRequest extends FormRequest
{
    /**
     * Found Log model container
     *
     * @var Log|null
     */
    private $log;

    /**
     * Found Logs model container
     *
     * @var Collection|array|null
     */
    private $logs;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch (true) {
            case Str::contains($this->url(), "view"):
                return $this->user()->fresh()->can("view-log", $this->getLog());

            case Str::contains($this->url(), "restore"):
                $this->merge(["force" => true]);
                return $this->user()->fresh()->can("restore-many-log", $this->getLogs());

            case Str::contains($this->url(), "delete") && ($this->get("force") == false):
                return $this->user()->fresh()->can("delete-many-log", $this->getLogs());
            case Str::contains($this->url(), "delete") && $this->get("force"):
                return $this->user()->fresh()->can("force-delete-many-log", $this->getLogs());

            default:
                return false;
        }
    }

    /**
     * Get Log based on supplied input
     *
     * @return Log
     */
    public function getLog()
    {
        if ($this->log) {
            return $this->log;
        }
        $id = $this->input('id') ?: $this->input('log_id');

        $withTrashed = $this->get("force");

        return $this->log = Log::withTrashed($withTrashed)->findOrFail($id);
    }

    /**
     * Get many log based on supplied input
     *
     * @return Collection|array|null
     */
    public function getLogs()
    {
        if ($this->logs)
            return $this->logs;

        $withTrashed = $this->get("force") || \Str::contains($this->url(), "restore");

        switch (true) {
            case $this->has('id') || $this->has('log_id'):
                $id = $this->id ?? $this->log_id;
                $this->logs = Log::withTrashed($withTrashed)
                    ->select(["id", "company_id", "created_at"])
                    ->setEagerLoads([])
                    ->where("company_id", $this->user()->company->id)
                    ->where("id", $id)
                    ->get()
                    ->makeHidden(["message", "properties"]);
                break;

            case $this->has('ids') || $this->has('log_ids'):
                $ids = $this->ids ?? $this->log_ids;
                $this->logs = Log::withTrashed($withTrashed)
                    ->select(["id", "company_id", "created_at"])
                    ->setEagerLoads([])
                    ->where("company_id", $this->user()->company->id)
                    ->whereIn("id", $ids)
                    ->get()
                    ->makeHidden(["message", "properties"]);
                break;

            case $this->has('start') && $this->has('end'):
                $this->logs = Log::withTrashed($withTrashed)
                    ->select(["id", "company_id", "created_at"])
                    ->setEagerLoads([])
                    ->where("company_id", $this->user()->company->id)
                    ->whereBetween("created_at", [
                        $this->start, $this->end
                    ])->get()
                    ->makeHidden(["message", "properties"]);
                break;

            case $this->has('datetime'):
                $datetime = $this->datetime;
                $this->logs = Log::withTrashed($withTrashed)
                    ->select(["id", "company_id", "created_at"])
                    ->setEagerLoads([])
                    ->where("company_id", $this->user()->company->id)
                    ->whereBetween("created_at", [
                        $datetime, $datetime->copy()->endOfHour()
                    ])->get()
                    ->makeHidden(["message", "properties"]);
                break;
        }

        return !is_null($this->logs) &&
        (($this->logs instanceof Collection && $this->logs->isNotEmpty()) || (is_array($this->logs) && !empty($this->logs)))
            ? $this->logs : abort(404, "Models / Logs Not Found");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "id" => "nullable|string",
            "log_id" => "nullable|string",

            "ids" => "nullable|array",
            "log_ids" => "nullable|array",

            "start" => "nullable|date",
            "end" => "nullable|date",

            "datetime" => "nullable|date",
        ];
    }

    /**
     * Prepare input requests before validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $force = $this->get('force', false);
        $this->merge(['force' => strtobool($force)]);

        if ($this->has('start') && $this->has('end')) :
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->get('start')); // * Example format : '2020-01-01 18:01:01'
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $this->get('end')); // * Example format : '2020-01-01 18:01:01'
            $this->merge(["start" => $start, "end" => $end]);
        endif;
        if ($this->has('datetime')) :
            // * Example format : '2020-01-01 18:01:01'
            $datetime = carbon()->createFromFormat("Y-m-d H:i:s", $this->get('datetime'))->startOfHour();
            $this->merge(["datetime" => $datetime]);
        endif;
    }
}
