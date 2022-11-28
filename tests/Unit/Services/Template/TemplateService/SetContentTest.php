<?php

namespace Tests\Unit\Services\Template\TemplateService;

/**
 * @see \App\Services\Template\TemplateService::setContent()
 *      To the tested service method.
 * @see https://app.clickup.com/t/34505pm
 *      To view tickets when they were created
 */
class SetContentTest extends TemplateServiceTest
{
    /**
     * Ensure the content sent is assigned
     * @test
     * @return void
     */
    public function ensure_the_content_sent_is_assigned_in_the_property(): void
    {
        $this->assertEmpty('', $this->service()->content);

        $text = "
            Name : <user_name>,
            Phone : <user_phone>,
            Email : <user_email>
        ";

        $this->service()->setContent($text);
        $this->assertNotEmpty($this->service()->content);
        $this->assertSame($text, $this->service()->content);
    }
}
