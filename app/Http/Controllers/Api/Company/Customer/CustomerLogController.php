<?php

namespace App\Http\Controllers\Api\Company\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Customer\PopulateCustomerLogsRequest;
use App\Repositories\Log\LogRepository;
use Illuminate\Http\JsonResponse;

/**
 * @see \Tests\Feature\Dashboard\Company\Customer\CustomerLogTest
 *      To the controller unit tester class.
 */
class CustomerLogController extends Controller
{
    /**
     * Log Repository Class Container
     *
     * @var LogRepository
     */
    private LogRepository $logRepository;

    /**
     * Controller constructor method
     *
     * @param LogRepository $logRepository
     */
    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * Populate customer logs up
     *
     * @param PopulateCustomerLogsRequest $request
     * @return JsonResponse
     */
    public function customerLogs(PopulateCustomerLogsRequest $request): JsonResponse
    {
        $options = $request->companyOptions();
        $logs = $this->logRepository->all($options);
        $logs = $this->logRepository->datePaginate();
        $logs = $this->logRepository->groupByDateAndHour();

        return response()->json(['logs' => $logs]);
    }
}
