<?php

namespace App\Repositories\Company;

use App\Models\{Company\Company, Owner\Owner, User\User};
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CompanyOwnerRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Owner());
    }

    /**
     * Get all inviteable owners
     *
     * @param array $options
     * @return Collection|LengthAwarePaginator
     */
    public function inviteables(array $options = []): LengthAwarePaginator|Collection
    {
        $options['wheres'][] = [
            'column' => 'user_id',
            'value' => null,
        ];

        return $this->all($options);
    }

    /**
     * Assign user to owner
     *
     * @param User $user
     * @return Owner|null
     */
    public function assignUser(User $user): ?Owner
    {
        try {
            $user->assignRole('owner');
            $owner = $this->save(['user_id' => $user->id]);
            $this->setModel($owner);
            $this->setSuccess('Successfully assign user as owner.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to assign user owner.', $error);
        }

        return $this->getModel();
    }

    /**
     * Save to create or update owner
     *
     * @param array $ownerData
     * @return Owner|null
     */
    public function save(array $ownerData): ?Owner
    {
        try {
            $owner = $this->getModel();
            $owner->fill($ownerData);

            $user = $owner->user;

            if (is_null($user)) {
                $user = User::find($owner->user_id);
            }


            if (array_key_exists('fullname', $ownerData)) {
                $user->fullname = $ownerData['fullname'];
            }
            if (array_key_exists('email', $ownerData)) {
                $user->email = $ownerData['email'];
            }
            if (array_key_exists('phone', $ownerData)) {
                $user->phone = $ownerData['phone'];
            }

            $user->save();

            $owner->save();
            $this->setModel($owner);
            $this->setSuccess('Successfully save owner.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to save owner.', $error);
        }

        return $this->getModel();
    }

    /**
     * Assign company to owner
     *
     * @param Company $company
     * @return Owner|null
     */
    public function assignCompany(Company $company): ?Owner
    {
        try {
            $owner = $this->getModel();
            $owner->company_id = $company->id;
            $owner->save();
            $this->setModel($owner);
            $this->setSuccess('Successfully assign company to owner.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to assign company to owner.', $error);
        }

        return $this->getModel();
    }

    /**
     * Replace prime owner of the company
     *
     * @return Owner|null
     */
    public function replacePrime(): ?Owner
    {
        try {
            $owner = $this->getModel();

            // remove old prime owner of the company
            $oldOwner = Owner::whereCompanyId($owner->company_id)
                ->primeOnly()
                ->first();
            $oldOwner->is_prime_owner = false;
            $oldOwner->save();

            // get old owner's permission
            $permissionNames = $oldOwner->user->permissions()->get(['name'])
                ->pluck('name')
                ->toArray();

            // set a new prime owner of the company
            $owner->is_prime_owner = true ;
            // set the new prime owner's permissions from the old owner's permissions
            $owner->user->syncPermissions($permissionNames);
            $owner->save();

            $this->setModel($owner);
            $this->setSuccess('Successfully replace prime owner of the company.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to replace prime owner of the company.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete company
     *
     * @param bool $force
     * @return bool
     */
    public function delete(bool $force = false): bool
    {
        try {
            $owner = $this->getModel();
            $force ? $owner->forceDelete() : $owner->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete owner from company');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete owner from company', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Upload owner avatar
     *
     * @param UploadedFile $image
     * @return Owner|null
     */
    public function setImage(UploadedFile $image): ?Owner
    {
        try {
            $owner = $this->getModel();
            $user = $owner->user;
            $user->profile_picture = $image;
            $user->save();
            $this->setModel($owner);
            $this->setSuccess('Successfully upload image owner.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to upload image owner.', $error);
        }

        return $this->getModel();
    }

    /**
     * Restore owner from soft-delete
     *
     * @return Owner|null
     */
    public function restore(): ?Owner
    {
        try {
            $owner = $this->getModel();
            $owner->restore();
            $this->setModel($owner);
            $this->setSuccess('Successfully restored owner.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to restore owner.', $error);
        }

        return $this->getModel();
    }
}
