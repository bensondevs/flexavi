<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\QuotationRevisions\{
    PopulateQuotationRevisionsRequest as PopulateRequest,
    SaveQuotationRevisionRequest as SaveRequest,
    ApplyQuotationRevisionRequest as ApplyRequest,
    DeleteQuotationRevisionRequest as DeleteRequest
};

use App\Repositories\QuotationRevisionRepository as RevisionRepository;

class QuotationRevisionController extends Controller
{
    /**
     * Quotation Revision Repository Class Container
     * 
     * @var \App\Repositories\RevisionRepository
     */
    private $revision;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\QuotationRevisionRepository  $revision
     * @return void
     */
    public function __construct(RevisionRepository $revision)
    {
        $this->revision = $revision;
    }

    /**
     * Populate quotation revisions
     * 
     * @param PopulateRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function quotationRevisions(PopulateRequest $request)
    {
        $options = $request->options();

        $revisions = $this->revision->all($options);
        $revisions = $this->revision->paginate();
        
        return response()->json(['revisions' => $revisions]);
    }

    /**
     * Store quotation revision
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function store(SaveRequest $request)
    {
        $input = $request->onlyInRules();
        $revision = $this->revision->save($input);

        return apiResponse($this->revision);
    }

    /**
     * Apply quotation revision
     * 
     * @param ApplyRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function apply(ApplyRequest $request)
    {
        $revision = $request->getRevision();
        $revision = $this->revision->setModel($revision);
        $revision = $this->revision->apply();

        return apiResponse($this->revision);
    }

    /**
     * Update quotation revision
     * 
     * @param SaveRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function update(SaveRequest $request)
    {
        $revision = $request->getRevision();
        $revision = $this->revision->setModel($revision);

        $input = $request->onlyInRules();
        $revision = $this->revision->save($input);

        return apiResponse($this->revision);
    }

    /**
     * Delete quotation revision
     * 
     * @param DeleteRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $revision = $request->getRevision();
        $this->revision->setModel($revision);

        $force = $request->input('force');
        $this->revision->delete($force);

        return apiResponse($this->revision);
    }
}
