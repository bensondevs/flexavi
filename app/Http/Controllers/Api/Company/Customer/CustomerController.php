<?php

namespace App\Http\Controllers\Api\Company\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\Company\Customers\{
    PopulateCompanyCustomersRequest as PopulateRequest,
    DeleteCustomerRequest as DeleteRequest,
    FindCustomerRequest as FindRequest,
    RestoreCustomerRequest as RestoreRequest,
    SaveCustomerRequest as SaveRequest,
    UpdateCustomerRequest as UpdateRequest,
};
use App\Http\Resources\Customer\CustomerResource;
use App\Repositories\Address\AddressRepository;
use App\Repositories\Customer\CustomerRepository;

/**
 * @see \Tests\Feature\Dashboard\Company\Customer\CustomerTest
 *      To the controller unit tester class.
 */
class CustomerController extends Controller
{
    /**
     * Customer Repository Class container
     *
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * Customer Repository Class container
     *
     * @var AddressRepository
     */
    private AddressRepository $addressRepository;

    /**
     * Controller constructor method
     *
     * @param CustomerRepository $customerRepository
     * @param AddressRepository $addressRepository
     */
    public function __construct(
        CustomerRepository $customerRepository,
        AddressRepository $addressRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * Populate company customers
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function companyCustomers(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $customers = $this->customerRepository->all($options);
        $customers = $this->customerRepository->paginate($options['per_page']);

        return response()->json([
            'customers' => CustomerResource::apiCollection($customers),
        ]);
    }

    /**
     * Populate soft-deleted customers
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function trashedCustomers(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $customers = $this->customerRepository->trasheds($options);
        $customers = $this->customerRepository->paginate($options['per_page']);

        return response()->json([
            'customers' => CustomerResource::apiCollection($customers),
        ]);
    }

    /**
     * View customer
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function view(FindRequest $request): JsonResponse
    {
        $customer = $request->getCustomer();

        return response()->json([
            'customer' => new CustomerResource($customer),
        ]);
    }

    /**
     * Store customer
     *
     * @param SaveRequest $request
     * @return JsonResponse
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $customer = $this->customerRepository->save($request->customerData());
        $this->addressRepository->setAddressable($customer);
        $this->addressRepository->save($request->addressData());

        return apiResponse($this->customerRepository, [
            'customer' => new CustomerResource($customer->fresh()),
        ]);
    }

    /**
     * Update customer
     *
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        $this->customerRepository->setModel($request->getCustomer());
        $customer = $this->customerRepository->save($request->customerData());

        // Update the address of customer
        $this->addressRepository->setAddressable($customer);
        $this->addressRepository->setModel($request->getCustomerAddress());
        $this->addressRepository->save($request->addressData());

        return apiResponse($this->customerRepository, [
            'customer' => new CustomerResource($customer->refresh()),
        ]);
    }

    /**
     * Restore customer
     *
     * @param RestoreRequest $request
     * @return JsonResponse
     */
    public function restore(RestoreRequest $request): JsonResponse
    {
        $this->customerRepository->setModel($request->getTrashedCustomer());
        $customer = $this->customerRepository->restore();

        return apiResponse($this->customerRepository, [
            'customer' => new CustomerResource($customer->refresh()),
        ]);
    }

    /**
     * Delete customer
     *
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        $this->customerRepository->setModel($request->getCustomer());
        $this->customerRepository->delete(strtobool($request->input('force')));

        return apiResponse($this->customerRepository);
    }
}
