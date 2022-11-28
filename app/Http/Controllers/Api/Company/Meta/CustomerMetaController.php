<?php

namespace App\Http\Controllers\Api\Company\Meta;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Customers\PopulateCustomerCityRequest;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CustomerMetaController extends Controller
{
    /**
     * Customer Repository Class container
     *
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * Controller constructor method
     *
     * @param CustomerRepository $customerRepository
     * @return void
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Populate available cities
     *
     * @param PopulateCustomerCityRequest $request
     * @return JsonResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function customerCities(PopulateCustomerCityRequest $request): JsonResponse
    {
        $company = $request->getCompany();

        $cities = $this->customerRepository->cities($company);

        return response()->json(['cities' => $cities]);
    }
}
