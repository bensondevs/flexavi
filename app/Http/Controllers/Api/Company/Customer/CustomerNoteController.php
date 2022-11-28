<?php

namespace App\Http\Controllers\Api\Company\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CustomerNotes\{DeleteCustomerNoteRequest as DeleteRequest,
    FindCustomerNoteRequest as FindRequest,
    PopulateCustomerNotesRequest as PopulateRequest,
    RestoreCustomerNoteRequest as RestoreRequest,
    SaveCustomerNoteRequest as SaveRequest
};
use App\Http\Resources\Customer\CustomerNoteResource;
use App\Repositories\Customer\CustomerNoteRepository;
use Illuminate\Http\JsonResponse;

/**
 * @TODO Re-run unit test and do refactor to the unit test
 * @TODO Build mode test case for each endpoint to ensure front-end can consume correctly
 */
class CustomerNoteController extends Controller
{
    /**
     * Customer note repository container variable
     *
     * @var CustomerNoteRepository
     */
    private CustomerNoteRepository $customerNoteRepository;

    /**
     * Controller constructor method
     *
     * @param CustomerNoteRepository $customerNoteRepository
     */
    public function __construct(CustomerNoteRepository $customerNoteRepository)
    {
        $this->customerNoteRepository = $customerNoteRepository;
    }

    /**
     * Populate company customer notes
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function customerNotes(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $customerNotes = $this->customerNoteRepository->all($options, true);
        $customerNotes = CustomerNoteResource::apiCollection($customerNotes);
        return response()->json(['customer_notes' => $customerNotes]);
    }

    /**
     * View company customer note
     *
     * @param FindRequest $request
     * @return JsonResponse
     */
    public function view(FindRequest $request): JsonResponse
    {
        $customerNote = $request->getCustomerNote();
        return response()->json([
            'customer_note' => new CustomerNoteResource($customerNote)
        ]);
    }

    /**
     * Store company work service
     *
     * @param SaveRequest $request
     * @return JsonResponse
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $input = $request->validated();
        $customerNote = $this->customerNoteRepository->save($input);
        return apiResponse($this->customerNoteRepository, [
            'customer_note' => new CustomerNoteResource($customerNote)
        ]);
    }

    /**
     * Update company work service
     *
     * @param SaveRequest $request
     * @return JsonResponse
     */
    public function update(SaveRequest $request)
    {
        $input = $request->validated();
        $customerNote = $request->getCustomerNote();
        $this->customerNoteRepository->setModel($customerNote);
        $customerNote = $this->customerNoteRepository->save($input);
        return apiResponse($this->customerNoteRepository, [
            'customer_note' => new CustomerNoteResource($customerNote),
        ]);
    }

    /**
     * Delete Work Service
     *
     * @param DeleteRequest $request
     * @return JsonResponse
     */
    public function delete(DeleteRequest $request): JsonResponse
    {
        $customerNote = $request->getCustomerNote();

        $this->customerNoteRepository->setModel($customerNote);
        $force = strtobool($request->input('force'));
        $this->customerNoteRepository->delete($force);

        return apiResponse($this->customerNoteRepository);
    }

    /**
     * Populate company soft-deleted customer notes
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     */
    public function trashedCustomerNotes(PopulateRequest $request): JsonResponse
    {
        $options = $request->options();
        $customerNotes = $this->customerNoteRepository->trasheds($options, true);

        return response()->json([
            'customer_notes' => CustomerNoteResource::apiCollection($customerNotes),
        ]);
    }

    /**
     * Restore car
     *
     * @param RestoreRequest $request
     * @return JsonResponse
     */
    public function restore(RestoreRequest $request): JsonResponse
    {
        $customerNote = $request->getCustomerNote();
        $customerNote = $this->customerNoteRepository->setModel($customerNote);
        $customerNote = $this->customerNoteRepository->restore();

        return apiResponse($this->customerNoteRepository, [
            'customer_note' => new CustomerNoteResource($customerNote)
        ]);
    }
}
