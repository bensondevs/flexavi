<?php

namespace App\Services\Template;

/**
 * @see https://app.clickup.com/t/34505pm
 *      To view tickets when they were created
 */
interface TemplateInterface
{
    public function initialize(...$parameters);

    public function execute();
}
