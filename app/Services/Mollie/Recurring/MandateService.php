<?php

namespace App\Services\Mollie\Recurring;

use App\Models\{Company\Company, Company\MollieCompanyMandate};
use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Resources\BaseCollection;
use Mollie\Api\Resources\BaseResource;
use Mollie\Api\Resources\Mandate;
use Mollie\Api\Resources\MandateCollection;
use Mollie\Laravel\Facades\Mollie;

class MandateService
{
    /**
     * Create Customer Mandate
     *
     * @param Company $company
     * @param array $data
     * @return BaseResource|Mandate
     * @throws ApiException
     */
    public function create(Company $company, array $data): Mandate|BaseResource
    {
        return Mollie::api()->mandates()->createForId($company->mollie_customer_id, [
            'method' => $data['method'], //possible value {directdebit,paypal},
            'consumerName' => $data['consumer_name'],
            'consumerAccount' => $data['consumer_iban'] ?? null,
            'consumerBic' => $data['consumer_bic'] ?? null,
            'consumerEmail' => $data['consumer_email'] ?? null,
            'signatureDate' => $data['signature_date'] ?? null,
            'mandateReference' => $data['mandate_reference'] ?? null,
            'paypalBillingAgreementId' => $data['paypal_billing_agreement_id'] ?? null
        ]);
    }

    /**
     * Find Customer Mandate
     *
     * @param Company $company
     * @param MollieCompanyMandate $mollieCompanyMandate
     * @return BaseResource|Mandate
     * @throws ApiException
     */
    public function find(Company $company, MollieCompanyMandate $mollieCompanyMandate): Mandate|BaseResource
    {
        return Mollie::api()->mandates()->getForId($company->mollie_customer_id, $mollieCompanyMandate->mandate_id);
    }

    /**
     * Revoke customer mandate
     *
     * @param Company $company
     * @param MollieCompanyMandate $mollieCompanyMandate
     * @return null
     * @throws ApiException
     */
    public function revoke(Company $company, MollieCompanyMandate $mollieCompanyMandate)
    {
        return Mollie::api()->mandates()->revokeForId($company->mollie_customer_id, $mollieCompanyMandate->mandate_id);
    }

    /**
     * Get customer mandates
     *
     * @param Company $company
     * @return BaseCollection|MandateCollection
     * @throws ApiException
     */
    public function get(Company $company): BaseCollection|MandateCollection
    {
        return Mollie::api()->mandates()->listForId($company->mollie_customer_id);
    }
}
