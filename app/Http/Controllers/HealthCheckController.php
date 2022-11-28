<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Health\Service;

class HealthCheckController extends Controller
{
    /**
     * @var Service
     */
    private Service $healthService;

    /**
     * Health constructor.
     *
     * @param Service $healthService
     */
    public function __construct(Service $healthService)
    {
        $this->middleware('health_check');
        $this->healthService = $healthService;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $this->healthService->setAction('panel');
        return response((string)view(config('health.views.panel'))->with('laravel', ['health' => config('health')]));
    }
}
