<?php

namespace App\Services\RouteXL;

use App\Models\Worklist\Worklist;
use App\Services\Appointment\AppointmentableSetOrderIndexService;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class RouteXLService
{
    /**
     * RouteXL max location per request
     *
     * @var string
     */
    private $maxLocation;

    /**
     * RouteXL Username
     *
     * @var string
     */
    private $username;

    /**
     * RouteXL Password
     *
     * @var string
     */
    private $password;

    /**
     * RouteXL Base URL
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Appointmentable Repository Container
     *
     * @var AppointmentableSetOrderIndexService
     */
    private AppointmentableSetOrderIndexService $appointmentableService;

    /**
     * Service constructor method
     *
     * @param AppointmentableSetOrderIndexService $appointmentableSetOrderIndexService
     * @return void
     */
    public function __construct(
        AppointmentableSetOrderIndexService $appointmentableSetOrderIndexService
    )
    {
        $this->username = config('routexl.auth.username');
        $this->password = config('routexl.auth.password');
        $this->baseUrl = config('routexl.base_url');
        $this->maxLocation = config('routexl.max_location_per_request') - 1;
        $this->appointmentableService = $appointmentableSetOrderIndexService;
    }

    /**
     *  Post new set of addresses, creating a new tour. For now,
     *  this immediately returns an optimized tour, the fastest route.
     *  In future, it wil return the tour ID for CRUD referral.
     * @param Worklist $worklist
     *
     * @return void
     * @throws Exception
     */
    public function planRoutes(Worklist $worklist): void
    {
        $url = $this->baseUrl . "tour";

        $appointments = $worklist->appointments;
        $companyAddress = $worklist->company->addresses[0];

        $companyLocation = [
            [
                'address' => $companyAddress->address . ', ' . $companyAddress->city . ', ' . $companyAddress->province,
                'lat' => $companyAddress->latitude,
                'lng' => $companyAddress->longitude
            ]
        ];

        $locations = $appointments->map(function ($item, $key) {
            $address = $item->customer->address;
            return [
                'address' => $address->route_xl_callable_address . ' ###' . $item->pivot->id,
                'lat' => $address->latitude,
                'lng' => $address->longitude
            ];
        })->take($this->maxLocation)->values()->toArray();

        $locations = array_merge($companyLocation, $locations);

        if (count($locations) > 1) {
            $response = Http::withBasicAuth($this->username, $this->password)->asForm()->post($url, [
                'locations' => $locations
            ]);

            $this->throwException($response);

            $response = $this->prepareResponse($response);

            $this->appointmentableService->handle($response);
        }
    }

    /**
     * @throws Exception
     */
    public function throwException($response): void
    {
        switch ($response->status()) {
            case 401:
                throw new Exception("RouteXL : Authentication problem", $response->status());
                break;
            case 403:
                throw new Exception("RouteXL : Too many locations for your subscription", $response->status());
                break;
            case 409:
                throw new Exception("RouteXL : No input or no locations found", $response->status());
                break;
            case 429:
                throw new Exception("RouteXL : Another route in progress", $response->status());
                break;
        }
    }

    /**
     * Get status of API server, its load and the load of the solvers.
     *
     * @return object
     */
    public function status(): object
    {
        $url = $this->baseUrl . "status";
        $username = $this->username;
        $password = $this->password;

        $response = Http::withBasicAuth($username, $password)->get($url);
        return $this->prepareResponse($response);
    }

    /**
     *  Prepare Response
     * @param Response $response
     *
     * @return object
     */
    public function prepareResponse($response): object
    {
        return json_decode($response->getBody()->getContents());
    }
}
