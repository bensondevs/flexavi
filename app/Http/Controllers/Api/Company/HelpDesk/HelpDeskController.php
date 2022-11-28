<?php

namespace App\Http\Controllers\Api\Company\HelpDesk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\HelpDesks\{
    PopulateHelpDesksRequest as PopulateRequest,
    HelpDeskActionRequest as ActionRequest
};
use App\Http\Resources\HelpDesk\HelpDeskResource as Resource ;
use App\Repositories\HelpDesk\HelpDeskRepository;
use Illuminate\Http\JsonResponse;

class HelpDeskController extends Controller
{
    /**
    * HelpDeskRepository Class Container
    *
    * @var HelpDeskRepository
    */
    private $helpDeskRepository;

    /**
     * Controller constructor method
     *
     * @param HelpDeskRepository $helpDeskRepository
     */
    public function __construct(HelpDeskRepository $helpDeskRepository)
    {
        $this->helpDeskRepository = $helpDeskRepository;
    }

    /**
     * Populate company helpDesks
     *
     * @param PopulateRequest $request
     * @return JsonResponse
     * @see Tests\Feature\Dashboard\Company\HelpDesk\HelpDeskTest::test_populate_help_desks()
     *      to controller's feature test
     */
    public function populate(PopulateRequest $request)
    {
        $options = $request->companyOptions();
        $helpDesks = $this->helpDeskRepository->all($options, true);
        $helpDesks = Resource::apiCollection($helpDesks);

        return response()->json([
            'help_desks' => $helpDesks,
        ]);
    }

    /**
     * Store HelpDesk
     *
     * @param ActionRequest $request
     * @return JsonResponse
     * @see Tests\Feature\Dashboard\Company\HelpDesk\HelpDeskTest::test_store_help_desk()
     *      to controller's feature test
     */
    public function view(ActionRequest $request)
    {
        $helpDesk = $request->getHelpDesk();

        return response()->json([
            'help_desk' => new Resource($helpDesk),
        ]);
    }

     /**
     * Store HelpDesk
     *
     * @param ActionRequest $request
     * @return JsonResponse
     * @see Tests\Feature\Dashboard\Company\HelpDesk\HelpDeskTest::test_store_help_desk()
     *      to controller's feature test
     */
    public function store(ActionRequest $request): JsonResponse
    {
        $helpDesk = $this->helpDeskRepository->save(array_merge([
            $request->validated() ,
            'company_id' => $request->getCompany()->id
        ]));
        return apiResponse($this->helpDeskRepository, [
            'help_desk' => new Resource($helpDesk)
        ]);
    }

     /**
     * Update a HelpDesk
     *
     * @param ActionRequest $request
     * @return JsonResponse
     * @see Tests\Feature\Dashboard\HelpDesk\HelpDeskTest::test_update_help_desk
     *   to controller's feature test
     */
    public function update(ActionRequest $request): JsonResponse
    {
        $this->helpDeskRepository->setModel($request->getHelpDesk());
        $this->helpDeskRepository->save($request->validated());
        return apiResponse($this->helpDeskRepository);
    }

    /**
     * Delete a HelpDesk
     *
     * @param ActionRequest $request
     * @return JsonResponse
     * @see Tests\Feature\Dashboard\HelpDesk\HelpDeskTest::test_delete_help_desk
     *   to controller's feature test
     */
    public function delete(ActionRequest $request): JsonResponse
    {
        $this->helpDeskRepository->setModel($request->getHelpDesk());
        $this->helpDeskRepository->delete();
        return apiResponse($this->helpDeskRepository);
    }
}
