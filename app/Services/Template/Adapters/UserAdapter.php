<?php

namespace App\Services\Template\Adapters;

use App\Models\User\User;

class UserAdapter
{
    /**
     * Get causer role name
     *
     * @param string $causerId
     * @return string
     */
    public function getUserRole(string $causerId): string
    {
        return User::findOrFail($causerId)->role_name;
    }

    /**
     * Get causer fullname
     *
     * @param string $causerId
     * @return string
     */
    public function getUserFullname(string $causerId): string
    {
        return User::findOrFail($causerId)->fullname;
    }

    /**
     * Get causer email
     *
     * @param string $causerId
     * @return string
     */
    public function getUserEmail(string $causerId): string
    {
        return User::findOrFail($causerId)->email;
    }
}
