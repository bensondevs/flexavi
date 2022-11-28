<?php

namespace App\Policies\Company\ExecuteWork;

use App\Models\ExecuteWork\ExecuteWork;
use App\Models\ExecuteWork\ExecuteWorkPhoto;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExecuteWorkPhotoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyDirectPermission($executeWork->company_id, 'view execute work photos');
    }

    public function upload(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyDirectPermission($executeWork->company_id, 'upload execute work photos');
    }

    public function delete(User $user, ExecuteWorkPhoto $executeWorkPhoto)
    {
        $executeWork = $executeWorkPhoto->executeWork;

        return $user->hasCompanyDirectPermission($executeWork->company_id, 'delete execute work photos');
    }
}
