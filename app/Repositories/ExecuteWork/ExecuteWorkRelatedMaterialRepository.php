<?php

namespace App\Repositories\ExecuteWork;

use App\Models\ExecuteWork\ExecuteWorkRelatedMaterial;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class ExecuteWorkRelatedMaterialRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new ExecuteWorkRelatedMaterial);
    }

    /**
     * Save execute work related material data
     *
     * @param  array  $relatedMaterialData
     * @return ExecuteWorkRelatedMaterial|null
     */
    public function save(array $relatedMaterialData)
    {
        try {
            $relatedMaterial = $this->getModel();
            $relatedMaterial->fill($relatedMaterialData);
            $relatedMaterial->save();
            if (isset($relatedMaterialData['quotation_file'])) $relatedMaterial->addMedia($relatedMaterialData['quotation_file'])->toMediaCollection('quotation_file');
            if (isset($relatedMaterialData['invoice_file'])) $relatedMaterial->addMedia($relatedMaterialData['invoice_file'])->toMediaCollection('invoice_file');
            if (isset($relatedMaterialData['work_contract_file'])) $relatedMaterial->addMedia($relatedMaterialData['work_contract_file'])->toMediaCollection('work_contract_file');
            $this->setModel($relatedMaterial);
            $this->setSuccess("Successfully save execute work related material.");
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError("Failed to save execute work related material.", $error);
        }
    }
}
