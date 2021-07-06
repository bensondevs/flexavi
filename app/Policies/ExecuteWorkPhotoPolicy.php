<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\ExecuteWork;
use App\Models\ExecuteWorkPhoto;

class ExecuteWorkPhotoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyPermission($executeWork->company_id, 'view execute work photos');
    }

    public function upload(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyPermission($executeWork->company_id, 'upload execute work photos');
    }

    public function delete(User $user, ExecuteWorkPhoto $executeWorkPhoto)
    {
        $parent = $executeWorkPhoto->parent;

        return $user->hasCompanyPermission($parent->company_id, 'delete execute work photos');
    }
}
