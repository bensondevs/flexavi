<?php

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class UserSocialiteAccountType extends Enum
{
    /**
     * Google authentication type
     *
     * @var int
     */
    const Google = 1;

    /**
     * Facebook's authentication type
     *
     * @var int
     */
    const Facebook = 2;

    /**
     * Twitter authentication type
     *
     * @var int
     */
    const Twitter = 3;

    /**
     * Get the socialite driver name
     *
     * @return string
     */
    public function getDriver(): string
    {
        return strtolower($this->getDescription($this->value));
    }
}
