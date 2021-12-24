<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentReminders\{
    PopulateCompanyPaymentReminderRequest as PopulateRequest,
    StorePaymentReminderRequest as StoreRequest,
    FindPaymentReminderRequest as FindRequest,
    UpdatePaymentReminderRequest as UpdateRequest,
    DeletePaymentReminderRequest as DeleteRequest,
    RestorePaymentReminderRequest as RestoreRequest,
    AddPaymentReminderableRequest as AddReminderableRequest,
    AddMultiplePaymentReminderablesRequest as AddMultipleReminderablesRequest,
    RemovePaymentReminderableRequest as RemoveReminderableRequest,
    RemoveMultiplePaymentReminderablesRequest as RemoveMultipleReminderablesRequest,
    TruncatePaymentReminderablesRequest as TruncateReminderablesRequest
};

use App\Repositories\PaymentReminderRepository;

class PaymentReminderController extends Controller
{
    /**
     * Reppository class container
     * 
     * @var \App\Repositories\PaymentReminderRepository
     */
    private $reminder;

    /**
     * Controller constructor method
     * 
     * @param  \App\Repositories\PaymentReminderRepository  $reminder
     * @return  void
     */
    public function __construct(PaymentReminderRepository $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Populate payment remidners
     * 
     * @param  PopulateRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function companyPaymentReminders(PopulateRequest $request)
    {
        $options = $request->options();
        $reminders = $this->reminder->all($options);
        $reminders = PaymentReminderResource::apiCollection($reminders);

        return response()->json(['payment_reminders' => $reminders]);
    }

    /**
     * Create payment reminder
     * 
     * @param  StoreRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function store(StoreRequest $request)
    {
        $input = $request->validated();
        $this->reminder->save($input);

        return apiResponse($this->reminder);
    }

    /**
     * View payment reminder
     * 
     * @param  FindRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function view(FindRequest $request)
    {
        $reminder = $request->getPaymentReminder();

        $relations = $request->relations();
        $reminder->load($relations);
        $reminder = new PaymentReminderResource($reminder);

        return response()->json(['payment_reminder' => $reminder]);
    }

    /**
     * Update payment reminder
     * 
     * @param UpdateRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function update(UpdateRequest $request)
    {
        $reminder = $request->getPaymentReminder();
        $this->reminder->setModel($reminder);

        $input = $request->validated();
        $this->reminder->save($input);

        return apiResponse($this->reminder);
    }

    /**
     * Delete payment reminder
     * 
     * @param  DeleteRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function delete(DeleteRequest $request)
    {
        $reminder = $request->getPaymentReminder();
        $this->reminder->setModel($reminder);

        $force = $request->input('force');
        $this->reminder->delete($force);

        return apiResponse($this->reminder);
    }

    /**
     * Restore payment reminder
     * 
     * @param  RestoreRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function restore(RestoreRequest $request)
    {
        $reminder = $request->getPaymentReminder();
        $this->reminder->setModel($reminder);

        $force = $request->input('force', false);
        $this->reminder->delete($force);

        return apiResponse($this->reminder);
    }

    /**
     * Add reminderable to payment reminder
     * 
     * @param  AddReminderableRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function addReminderable(AddReminderableRequest $request)
    {
        $reminder = $request->getPaymentReminder();
        $this->reminder->setModel($reminder);

        $reminderable = $request->getReminderable();
        $this->reminder->addReminderable($reminderable);

        return apiResponse($this->reminder);
    }

    /**
     * Add multiple reminderable to payment reminder
     * 
     * @param  AddMultipleReminderableRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function addMultipleReminderables(AddMultipleReminderablesRequest $request)
    {
        //
    }

    /**
     * Remove reminderable from payment reminder
     * 
     * @param  RemoveReminderableRequest  $request
     * @return  \Illuminate\Support\Facades\Response
     */
    public function removeReminderable(RemoveReminderableRequest $request)
    {
        //
    }

    /**
     * Remove multiple reminderables from payment reminder
     * 
     * @param  RemoveMultipleReminderable
     */
    public function removeMultipleReminderables()
    {
        //
    }
}