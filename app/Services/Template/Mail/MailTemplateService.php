<?php

namespace App\Services\Template\Mail;

use App\Services\Template\{Adapters\CustomerAdapter, Adapters\UserAdapter, TemplateInterface, TemplateService};
use PHPStan\DependencyInjection\ParameterNotFoundException;

/**
 * @see https://app.clickup.com/t/34505pm
 *      To view tickets when they were created
 */
class MailTemplateService extends TemplateService implements TemplateInterface
{
    /**
     * Configurations of mail template
     */
    const CONFIGURATIONS = [
        'customer_name' => [
            'adapter' => CustomerAdapter::class,
            'method' => 'getCustomerFullName',
            'parameters' => ['customerId']
        ],
        'user_name' => [
            'adapter' => UserAdapter::class,
            'method' => 'getUserFullName',
            'parameters' => ['userId']
        ]
    ];

    /**
     * Parameters
     *
     * @var array
     */
    private array $parameters;


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
     * @throws ParameterNotFoundException
     */
    public function execute(): self
    {
        parent::replace(
            self::CONFIGURATIONS,
            $this->parameters
        );
        return $this;
    }
}
