<?php

namespace App\Services\Template;

use Arr;
use PHPStan\DependencyInjection\ParameterNotFoundException;
use ReflectionException;
use ReflectionMethod;
use Str;

/**
 * @see https://app.clickup.com/t/34505pm
 *      To view tickets when they were created
 * @see \Tests\Unit\Services\Template\TemplateService\TemplateServiceTest
 *      To see service test class
 */
class TemplateService
{
    /**
     * Opening tag
     */
    const TEMPLATE_OPENING_TAG = '<';

    /**
     * Closing tag
     */
    const TEMPLATE_CLOSING_TAG = '>';

    /**
     * Content variable
     *
     * @var string
     */
    public string $content = '';

    /**
     * Set content
     *
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Returning content with replace variables
     *
     * @return string
     */
    public function render(): string
    {
        return $this->content;
    }

    /**
     * Replace tag with value
     *
     * @param array $configurations
     * @param array $parameters
     * @return $this
     * @throws ParameterNotFoundException
     * @throws ReflectionException
     */
    public function replace(array $configurations, array $parameters): self
    {
        $variables = self::findVariables();
        $this->checkRequiredParameters($configurations, $parameters, $variables);

        $content = $this->content;
        foreach ($variables as $variable) {
            if (!array_key_exists($variable, $configurations)) {
                abort(422, "Call to undefined method $variable in adapter class or variable content does not exist in configurations with parameters : " . json_encode($parameters));
            }
            $parsedParameters = [];

            $adapterMethod = $configurations[$variable]['method'];
            $adapterClass = $configurations[$variable]['adapter'];
            $adapterMethodArguments = (new ReflectionMethod($adapterClass, $adapterMethod))->getParameters();

            foreach ($adapterMethodArguments as $adapterMethodArgument) {
                $parsedParameters[$adapterMethodArgument->getName()] = $parameters[$configurations[$variable]['parameters'][$adapterMethodArgument->getName()]];
            }

            $callFunction = (new $adapterClass)->$adapterMethod(...$parsedParameters);

            $content = str_replace(
                self::TEMPLATE_OPENING_TAG . $variable . self::TEMPLATE_CLOSING_TAG,
                $callFunction,
                $content
            );
        }

        $this->content = $content;

        return $this;
    }

    /**
     * Find variables
     *
     * @return array
     */
    public function findVariables(): array
    {
        $pattern = '/(' . self::TEMPLATE_OPENING_TAG . '(.*?)' . self::TEMPLATE_CLOSING_TAG . ')/';
        preg_match_all($pattern, $this->content, $match, PREG_PATTERN_ORDER);
        $variables = array_unique($match[2]);
        return array_filter($variables, function ($item) {
            return !Str::contains($item, "DIRECT_VALUE.");
        });
    }

    /**
     * This function to check required parameters in adapter method
     *
     * @param array $configurations
     * @param array $parameters
     * @param array $variables
     * @return void
     * @throws ParameterNotFoundException
     */
    private function checkRequiredParameters(array $configurations, array $parameters, array $variables): void
    {
        $requiredParameters = Arr::flatten(
            collect($configurations)
                ->filter(function ($item, $key) use ($variables) {
                    return in_array($key, $variables);
                })
                ->pluck('parameters')
                ->toArray()
        );
        foreach ($requiredParameters as $requiredParameter) {
            if (!in_array($requiredParameter, array_keys($parameters))) {
                throw new ParameterNotFoundException($requiredParameter);
            }
        }
    }

    /**
     * Find variables
     *
     * @return array
     */
    public function findDirectValueVariables(): array
    {
        $pattern = '/(' . self::TEMPLATE_OPENING_TAG . '(.*?)' . self::TEMPLATE_CLOSING_TAG . ')/';
        preg_match_all($pattern, $this->content, $match, PREG_PATTERN_ORDER);
        $variables = array_unique($match[2]);
        return array_filter($variables, function ($item) {
            return Str::contains($item, "DIRECT_VALUE.");
        });
    }
}
