<?php

namespace App\Repositories\Company;

use App\Models\Company\Company;
use App\Repositories\Base\BaseRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class CompanyRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Company());
    }

    /**
     * Save company for creating or updating company
     *
     * @param  array  $data
     * @return Company|null
     */
    public function save(array $data)
    {
        try {
            $company = $this->getModel();
            $company->fill(Arr::only($data, [
                "company_name",
                "email",
                "phone_number",
                "vat_number",
                "commerce_chamber_number",
                "company_website_url",
            ]));
            $company->save();
            if ($visitingAddress = $data['visiting_address'] ?? null) {
                // delete old company's addresses
                $company->addresses()->delete();

                $company->visiting_address = $visitingAddress;

                // by default if the invoicing address is not provided set it value with visiting_address
                $invoicingAddress = ($data['invoicing_address'] ?? $visitingAddress) ;
                $company->invoicing_address = $invoicingAddress;
            }
            $this->setModel($company);
            $this->setSuccess('Successfully save company data.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save company data.', $error);
        }

        return $this->getModel();
    }

    /**
     * Upload company logo
     *
     * @param UploadedFile $logo
     * @return Company|null
     */
    public function uploadLogo(UploadedFile $logo): ?Company
    {
        try {
            $company = $this->getModel();
            $company->logo = $logo;
            $company->save();
            $this->setModel($company);
            $this->setSuccess('Successfully upload company logo');
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed to upload company logo.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete company
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        try {
            $company = $this->getModel();
            $force ? $company->forceDelete() : $company->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete company');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete company', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Restore soft-deleted company
     *
     * @return Company|null
     */
    public function restore(): ?Company
    {
        try {
            $company = $this->getModel();
            $company->restore();
            $this->setModel($company);
            $this->setSuccess('Successfully restore company');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore company', $error);
        }

        return $this->getModel();
    }
}
