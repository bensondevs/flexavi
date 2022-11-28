<?php

namespace App\Repositories\Invitation;

use App\Models\Invitation\RegisterInvitation;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class PendingInvitationRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new RegisterInvitation());
    }

    /**
     * Cancel the pending invitation
     *
     * @return bool
     */
    public function cancel()
    {
        try {
            $invitation = $this->getModel();
            $invitation->delete();

            $this->destroyModel();

            $this->setSuccess('Successfully cancel invitation.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to cancel invitation.', $error);
        }

        return $this->returnResponse();
    }
}
