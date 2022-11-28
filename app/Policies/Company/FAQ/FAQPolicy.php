<?php

namespace App\Policies\Company\FAQ;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FAQPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any faqs');
    }

    public function viewFaq(User $user)
    {
        return $user->hasDirectPermissionTwo('view faqs');
    }
}
