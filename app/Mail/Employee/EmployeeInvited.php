<?php

namespace App\Mail\Employee;

use App\Models\Employee\EmployeeInvitation;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeInvited extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * SendEmployeeInvitation object
     *
     * @var EmployeeInvitation
     */
    private $employeeInvitation;

    /**
     * User object
     *
     * @var User
     */
    private User $user;


    /**
     * Create a new message instance.
     *
     * @param EmployeeInvitation $employeeInvitation
     * @return void
     */
    public function __construct(EmployeeInvitation $employeeInvitation)
    {
        $this->employeeInvitation = $employeeInvitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.invitations.employee', [
            'invitation' => $this->employeeInvitation
        ]);
    }
}
