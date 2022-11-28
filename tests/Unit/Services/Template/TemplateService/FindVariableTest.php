<?php

namespace Tests\Unit\Services\Template\TemplateService;

use Illuminate\Foundation\Testing\WithFaker;

/**
 * @see \App\Services\Template\TemplateService::findVariables()
 *      To the tested service method.
 * @see https://app.clickup.com/t/34505pm
 *      To view tickets when they were created
 */
class FindVariableTest extends TemplateServiceTest
{
    use WithFaker;

    /**
     * Ensure return value from method is array
     *
     * @test
     * @return void
     */
    public function ensure_return_value_is_array(): void
    {
        $this->setUpMethod();
        $response = $this->service()->findVariables();
        $this->assertIsArray($response);
    }

    /**
     * Setup method to call `findVariables` method
     *
     * @param array $variables
     * @return void
     */
    public function setUpMethod(array $variables = []): void
    {
        if (count($variables)) {
            $text = "";
            foreach ($variables as $variable) {
                $text .= $this->faker->word . ' : ' . '<' . $variable . '>  ';
            }
        } else {
            $text = "
                Name : <user_name>,
                Phone : <user_phone>,
                Email : <user_email>
            ";
        }
        $this->service()->setContent($text);
    }

    /**
     * ensure the return value is as requested
     *
     * @test
     * @return void
     */
    public function ensure_the_return_value_is_as_requested(): void
    {
        $variables = $this->faker->words(rand(3, 8));
        $this->setUpMethod($variables);
        $response = $this->service()->findVariables();
        foreach ($variables as $variable) {
            $this->assertTrue(in_array($variable, $response));
        }
    }
}
