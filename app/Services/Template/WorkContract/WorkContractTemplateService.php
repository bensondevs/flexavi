<?php

namespace App\Services\Template\WorkContract;

use App\Models\WorkContract\WorkContract;
use App\Services\Template\Adapters\CompanyAdapter;
use App\Services\Template\Adapters\CustomerAdapter;
use App\Services\Template\Adapters\WorkContractAdapter;
use App\Services\Template\TemplateInterface;
use App\Services\Template\TemplateService;
use Arr;
use PHPStan\DependencyInjection\ParameterNotFoundException;
use ReflectionException;

class WorkContractTemplateService extends TemplateService implements TemplateInterface
{
    /**
     * Configurations of log template
     */
    const CONFIGURATIONS = [
        // Company
        'COMPANY_NAME' => [
            'direct_value' => true,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyName',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],
        'COMPANY_EMAIL' => [
            'direct_value' => true,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyEmail',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],
        'COMPANY_PHONE_NUMBER' => [
            'direct_value' => true,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyPhoneNumber',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],
        'COMPANY_VAT_NUMBER' => [
            'direct_value' => true,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyVatNumber',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],
        'COMPANY_ADDRESS' => [
            'direct_value' => false,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyAddress',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],
        'COMPANY_CITY' => [
            'direct_value' => false,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyCity',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],
        'COMPANY_ZIPCODE' => [
            'direct_value' => false,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyZipCode',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],
        'COMPANY_HOUSE_NUMBER' => [
            'direct_value' => false,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyHouseNumber',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],
        'COMPANY_PROVINCE' => [
            'direct_value' => false,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyCity',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],
        'COMPANY_WEBSITE' => [
            'direct_value' => false,
            'adapter' => CompanyAdapter::class,
            'method' => 'getCompanyWebsite',
            'parameters' => [
                'companyId' => 'companyId'
            ],
        ],

        // Customer
        'CUSTOMER_FULLNAME' => [
            'direct_value' => false,
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerFullname',
            'parameters' => [
                'customerId' => 'customerId'
            ],
        ],
        'CUSTOMER_EMAIL' => [
            'direct_value' => false,
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerEmail',
            'parameters' => [
                'customerId' => 'customerId'
            ],
        ],
        'CUSTOMER_PHONE' => [
            'direct_value' => false,
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerPhone',
            'parameters' => [
                'customerId' => 'customerId'
            ],
        ],
        'CUSTOMER_ACQUIRED_THROUGH_DESCRIPTION' => [
            'direct_value' => false,
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerAcquiredThroughDescription',
            'parameters' => [
                'customerId' => 'customerId'
            ],
        ],
        'CUSTOMER_ADDRESS' => [
            'direct_value' => false,
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerAddress',
            'parameters' => [
                'customerId' => 'customerId'
            ],
        ],
        'CUSTOMER_CITY' => [
            'direct_value' => false,
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerCity',
            'parameters' => [
                'customerId' => 'customerId'
            ],
        ],
        'CUSTOMER_ZIPCODE' => [
            'direct_value' => false,
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerZipCode',
            'parameters' => [
                'customerId' => 'customerId'
            ],
        ],
        'CUSTOMER_HOUSE_NUMBER' => [
            'direct_value' => false,
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerHouseNumber',
            'parameters' => [
                'customerId' => 'customerId'
            ],
        ],
        'CUSTOMER_PROVINCE' => [
            'direct_value' => false,
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerCity',
            'parameters' => [
                'customerId' => 'customerId'
            ],
        ],

        // Work contract
        'DATE' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getDate',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'CURRENT_DATE' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getCurrentDate',
            'parameters' => [],
        ],
        'AMOUNT' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getAmount',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'FORMATTED_AMOUNT' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getFormattedAmount',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'TOTAL_AMOUNT' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getTotalAmount',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'FORMATTED_TOTAL_AMOUNT' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getFormattedTotalAmount',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'TOTAL_AMOUNT_EXCLUDING_TAX' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getTotalAmountExcludingTax',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'FORMATTED_TOTAL_AMOUNT_EXCLUDING_TAX' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getFormattedTotalAmountExcludingTax',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'TAX_PERCENTAGE' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getTaxPercentage',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'FORMATTED_TAX_PERCENTAGE' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getFormattedTaxPercentage',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'TAX_AMOUNT' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getTaxAmount',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
        'FORMATTED_TAX_AMOUNT' => [
            'direct_value' => true,
            'adapter' => WorkContractAdapter::class,
            'method' => 'getFormattedTaxAmount',
            'parameters' => [
                'workContractId' => 'workContractId'
            ],
        ],
    ];

    /**
     * Parameters
     *
     * @var array
     */
    private array $parameters;

    /**
     * Work contract model.
     *
     * @param array $parameters
     */
    private WorkContract $workContract;

    /**
     * Service constructor method
     *
     * @param WorkContract|null $workContract
     */
    public function __construct(WorkContract $workContract = null)
    {
        $this->workContract = $workContract;
    }

    /**
     * Setup adapter for class
     *
     * @param mixed ...$parameters
     * @return self
     */
    public function initialize(...$parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Execute script to replace variable with value
     *
     * @return $this
     * @throws ParameterNotFoundException|ReflectionException|ReflectionException
     */
    public function execute(): self
    {
        parent::replace(
            self::CONFIGURATIONS,
            $this->parameters
        );
        return $this;
    }

    /**
     * Get required parameters
     *
     * @return array
     */
    public function getRequiredParameters(): array
    {
        $variables = parent::findVariables();
        $requiredParameters = Arr::flatten(
            collect(self::CONFIGURATIONS)
                ->filter(function ($item, $key) use ($variables) {
                    return in_array($key, $variables);
                })
                ->pluck('parameters')
                ->toArray()
        );
        return array_unique($requiredParameters);
    }
}
