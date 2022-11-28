<?php

namespace App\Mail\Employee;

use App\Models\Employee\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeePasswordReseted extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Employee object
     *
     * @var Employee
     */
    private $employee;

    /**
     * Create a new message instance.
     *
     * @param Employee $employee
     * @return void
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee->loadMissing('user');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.auths.employee-password-reseted', [
            'employee' => $this->employee
        ]);
    }
}
