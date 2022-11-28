<?php

namespace App\Services\Template\Log;

use App\Models\Log\Log;
use App\Services\Template\Adapters\CustomerAdapter;
use App\Services\Template\Adapters\UserAdapter;
use App\Services\Template\TemplateInterface;
use App\Services\Template\TemplateService;
use Arr;
use PHPStan\DependencyInjection\ParameterNotFoundException;
use ReflectionException;

class LogTemplateService extends TemplateService implements TemplateInterface
{
    /**
     * Configurations of log template
     */
    const CONFIGURATIONS = [
        // Causers
        'CAUSER_ROLE' => [
            'direct_value' => false,
            'adapter' => UserAdapter::class,
            'method' => 'getUserRole',
            'parameters' => [
                'causerId' => 'causerId'
            ],
        ],
        'CAUSER_FULLNAME' => [
            'direct_value' => false,
            'adapter' => UserAdapter::class,
            'method' => 'getUserFullname',
            'parameters' => [
                'causerId' => 'causerId'
            ],
        ],
        'CAUSER_EMAIL' => [
            'direct_value' => false,
            'adapter' => UserAdapter::class,
            'method' => 'getUserEmail',
            'parameters' => [
                'causerId' => 'causerId'
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
    ];
    /**
     * Log model container
     *
     * @var ?Log
     */
    private ?Log $log = null;
    /**
     * Parameters
     *
     * @var array
     */
    private array $parameters;

    /**
     * Service constructor method
     *
     * @param Log|null $log
     */
    public function __construct(Log $log = null)
    {
        $this->log = $log;
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
     * @throws ParameterNotFoundException|ReflectionException
     */
    public function execute(): self
    {
        $this->replaceDirectValueVariables();
        parent::replace(
            self::CONFIGURATIONS,
            $this->parameters
        );
        return $this;
    }

    /**
     * Replace direct value with available value
     *
     * @return void
     */
    public function replaceDirectValueVariables(): void
    {
        $content = $this->content;
        $variables = parent::findDirectValueVariables();
        foreach ($variables as $variable) {
            $properties = $this->log->properties;
            $key = strtolower(str_replace("DIRECT_VALUE.", "", $variable));
            $value = arrayobject_accessor($properties, $key);

            $content = str_replace(
                self::TEMPLATE_OPENING_TAG . $variable . self::TEMPLATE_CLOSING_TAG,
                $value,
                $content
            );
        }
        $this->content = $content;
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
