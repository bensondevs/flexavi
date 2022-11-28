<?php

namespace App\Services\Template\Adapters;

use App\Models\Company\Company;

class CompanyAdapter
{
    /**
     * Get company name
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyName(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        return $company->company_name;
    }

    /**
     * Get company mail
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyEmail(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        return $company->email;
    }

    /**
     * Get company phone number
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyPhoneNumber(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        return $company->phone_number;
    }

    /**
     * Get company logo path
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyLogoPath(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        return $company->company_logo_path;
    }

    /**
     * Get company logo url
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyLogoUrl(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        return $company->company_logo_url;
    }

    /**
     * Get company logo url
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyWebsite(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        return $company->company_website_url;
    }

    /**
     * Get company vat number
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyVatNumber(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        return $company->vat_number;
    }

    /**
     * Get customer address
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyAddress(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        $companyAddress = $company->visitingAddress;
        return $companyAddress->address;
    }

    /**
     * Get customer city
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyCity(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        $companyAddress = $company->visitingAddress;
        return $companyAddress->city;
    }

    /**
     * Get customer zip code
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyZipCode(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        $companyAddress = $company->visitingAddress;
        return $companyAddress->zipcode;
    }

    /**
     * Get customer house number
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyHouseNumber(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        $companyAddress = $company->visitingAddress;
        return $companyAddress->house_number;
    }

    /**
     * Get customer province
     *
     * @param string $companyId
     * @return string
     */
    public function getCompanyProvince(string $companyId): string
    {
        $company = Company::findOrFail($companyId);
        $companyAddress = $company->visitingAddress;
        return $companyAddress->province;
    }
}
