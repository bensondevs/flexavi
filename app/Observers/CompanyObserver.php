<?php

namespace App\Observers;

use App\Jobs\Workday\GenerateWorkdayForCreatedCompanyJob;
use App\Models\Company\Company;
use App\Services\Log\LogService;
use App\Services\Mollie\Recurring\CustomerService;
use App\Services\Notification\NotificationService;
use Mollie\Api\Exceptions\ApiException;

class CompanyObserver
{
    private CustomerService $mollieCustomerService;

    public function __construct(CustomerService $customerService)
    {
        $this->mollieCustomerService = $customerService;
    }

    /**
     * Handle the Company "creating" event.
     *
     * @param Company $company
     * @return void
     */
    public function creating(Company $company): void
    {
        if (!$company->id) {
            $company->id = generateUuid();
        }
    }

    /**
     * Handle the Company "created" event.
     *
     * @param Company $company
     * @return void
     */
    public function created(Company $company): void
    {
        dispatch(new GenerateWorkdayForCreatedCompanyJob($company));
        if ($user = auth()->user()) {
            LogService::make("company.store")
                ->by($user)
                ->on($company)
                ->write();
        }
    }

    /**
     * Handle the Customer "updating" event.
     *
     * @param Company $company
     * @return void
     */
    public function updating(Company $company): void
    {
        session()->put("props.old.company", $company->getOriginal());
    }

    /**
     * Handle the Company "updated" event.
     *
     * @param Company $company
     * @return void
     */
    public function updated(Company $company): void
    {
        if ($user = auth()->user()) {
            NotificationService::make("company.updated")
                ->by($user)
                ->on($company)
                ->extras($company->toArray())
                ->writeToOwners();

            if ($company->isDirty('company_name'))
                LogService::make("company.updates.company_name")
                    ->with("old.subject.company_name", session("props.old.company")["company_name"])
                    ->by($user)->on($company)->write();
            if ($company->isDirty('email'))
                LogService::make("company.updates.email")
                    ->with("old.subject.email", session("props.old.company")["email"])
                    ->by($user)->on($company)->write();
            if ($company->isDirty('phone_number'))
                LogService::make("company.updates.phone_number")
                    ->with("old.subject.phone_number", session("props.old.company")["phone_number"])
                    ->by($user)->on($company)->write();
            if ($company->isDirty('vat_number'))
                LogService::make("company.updates.vat_number")
                    ->with("old.subject.vat_number", session("props.old.company")["vat_number"])
                    ->by($user)->on($company)->write();
            if ($company->isDirty('commerce_chamber_number'))
                LogService::make("company.updates.commerce_chamber_number")
                    ->with("old.subject.commerce_chamber_number", session("props.old.company")["commerce_chamber_number"])
                    ->by($user)->on($company)->write();
            if ($company->isDirty('company_website_url'))
                LogService::make("company.updates.company_website_url")
                    ->with("old.subject.company_website_url", session("props.old.company")["company_website_url"])
                    ->by($user)->on($company)->write();
            if ($company->isDirty('mollie_customer_id'))
                LogService::make("company.updates.mollie_customer_id")
                    ->with("old.subject.mollie_customer_id", session("props.old.company")["mollie_customer_id"])
                    ->by($user)->on($company)->write();
        }

        session()->forget("props.old.company");
    }

    /**
     * Handle the Company "saved" event.
     *
     * @param Company $company
     * @return void
     * @throws ApiException
     */
    public function saved(Company $company): void
    {
        $service = $this->mollieCustomerService->createOrUpdate($company);
        $company->mollie_customer_id = $service->id;
        $company->saveQuietly();
    }

    /**
     * Handle the Company "deleted" event.
     *
     * @param Company $company
     * @return void
     */
    public function deleted(Company $company): void
    {
        $company->willBeDestroyedInDays(14);
        if ($user = auth()->user())
            LogService::make("company.delete")->by($user)->on($company)->write();
    }

    /**
     * Handle the Company "restored" event.
     *
     * @param Company $company
     * @return void
     */
    public function restored(Company $company): void
    {
        if ($user = auth()->user())
            LogService::make("company.restore")->by($user)->on($company)->write();
    }

    /**
     * Handle the Company "force deleted" event.
     *
     * @param Company $company
     * @return void
     */
    public function forceDeleted(Company $company): void
    {
        if ($user = auth()->user())
            LogService::make("company.force_delete")->by($user)->on($company)->write();
    }
}
