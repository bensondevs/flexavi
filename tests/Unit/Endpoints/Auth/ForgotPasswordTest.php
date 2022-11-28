<?php

namespace Tests\Unit\Endpoints\Auth;

use App\Models\User\User;
use Tests\TestCase;


class ForgotPasswordTest extends TestCase
{
    /**
     * Test search account by email
     *
     * @return void
     */
    public function test_load_user_image(): void
    {
        $user = User::factory()->create();
        $response = $this->get($user->profile_picture_url);
        $response->assertValid();
    }
}
