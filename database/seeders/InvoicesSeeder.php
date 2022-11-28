<?php

namespace Database\Seeders;

use App\Enums\Invoice\{InvoicePaymentMethod, InvoiceReminderSentType, InvoiceStatus};
use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\Invoice\InvoiceLog;
use App\Models\Invoice\InvoiceReminder;
use App\Models\Setting\InvoiceSetting;
use App\Models\User\User;
use App\Models\WorkService\WorkService;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class InvoicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Invoice::whereNotNull('id')->forceDelete();
        $faker = Factory::create();
        $rawInvoices = [];
        $rawInvoiceItems = [];
        $rawInvoiceReminders = [];
        $rawSettings = [];
        $rawLogs = [];

        foreach (Company::all() as $company) {
            foreach (InvoiceStatus::getValues() as $status) {
                for ($i = 0; $i < 10; $i++) {
                    $invoiceId = generateUuid();

                    $dueDate = Carbon::now();

                    $customer = Customer::where('company_id', $company->id)->inRandomOrder()->first();
                    if (!$customer) {
                        $customer = Customer::factory()->for($company)->create();
                    }

                    $invoice = [
                        'id' => $invoiceId,
                        'customer_id' => $customer->id,
                        'company_id' => $customer->company_id,
                        'number' => randomString(),
                        'date' => now()->subDays(3),
                        'due_date' => $dueDate,
                        'status' => $status,
                        'amount' => rand(100, 300),
                        'discount_amount' => rand(10, 30),
                        'potential_amount' => $faker->randomElements([0, rand(100, 300)], 1)[0],
                        'total_amount' => rand(100, 300),
                        'taxes' => json_encode([
                            [
                                "total" => rand(100, 300),
                                "sub_total" => rand(100, 300),
                                "tax_amount" => rand(100, 300),
                                "tax_percentage" => 5
                            ]
                        ]),
                        'customer_address' => $faker->address,
                        'payment_method' => InvoicePaymentMethod::getRandomValue(),
                        'note' => $faker->text,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $rawInvoices[] = $invoice;

                    for ($j = 0; $j < rand(2, 5); $j++) {
                        $workService = WorkService::where('company_id', $company->id)->inRandomOrder()->first();
                        if (!$workService) {
                            $workService = WorkService::factory()->for($company)->create();
                        }
                        $rawInvoiceItems[] = [
                            'id' => generateUuid(),
                            'invoice_id' => $invoiceId,
                            'work_service_id' => $workService->id,
                            'unit_price' => $workService->price,
                            'amount' => rand(1, 3),
                            'tax_percentage' => rand(5, 15),
                            'total' => $workService->price * rand(1, 3),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    $invoiceSetting = [
                        'id' => generateUuid(),
                        'invoice_id' => $invoiceId,
                        'auto_reminder_activated' => true,
                        'first_reminder_type' => InvoiceReminderSentType::getRandomValue(),
                        'first_reminder_days' => rand(1, 3),
                        'second_reminder_type' => InvoiceReminderSentType::getRandomValue(),
                        'second_reminder_days' => rand(1, 3),
                        'third_reminder_type' => InvoiceReminderSentType::getRandomValue(),
                        'third_reminder_days' => rand(1, 3),
                        'debt_collector_reminder_type' => InvoiceReminderSentType::getRandomValue(),
                        'debt_collector_reminder_days' => rand(1, 3),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $rawSettings[] = $invoiceSetting;

                    $rawInvoiceReminders[] = $this->generateInvoiceReminder($invoice, $invoiceSetting);
                }
            }
        }


        foreach (array_chunk($rawInvoices, 700) as $rawInvoicesChunk) {
            Invoice::insert($rawInvoicesChunk);
        }

        foreach (array_chunk($rawInvoiceItems, 700) as $rawInvoiceItemsChunk) {
            InvoiceItem::insert($rawInvoiceItemsChunk);
        }

        foreach (array_chunk($rawInvoiceReminders, 700) as $rawInvoiceRemindersChunk) {
            InvoiceReminder::insert($rawInvoiceRemindersChunk);
        }

        foreach (array_chunk($rawSettings, 700) as $rawSettingsChunk) {
            InvoiceSetting::insert($rawSettingsChunk);
        }

        foreach (array_chunk($rawLogs, 1000) as $rawLogsChunk) {
            InvoiceLog::insert($rawLogsChunk);
        }
    }


    /**
     * Generate invoice reminder
     *
     * @param array $invoice
     * @param array $invoiceSetting
     * @return array
     */
    private function generateInvoiceReminder(array $invoice, array $invoiceSetting): array
    {
        $reminder = [
            'id' => generateUuid(),
            'invoice_id' => $invoice['id'],
            'first_reminder_at' => null,
            'user_first_reminder_sent_at' => null,
            'customer_first_reminder_sent_at' => null,
            'second_reminder_at' => null,
            'user_second_reminder_sent_at' => null,
            'customer_second_reminder_sent_at' => null,
            'third_reminder_at' => null,
            'user_third_reminder_sent_at' => null,
            'customer_third_reminder_sent_at' => null,
            'sent_to_debt_collector_at' => null,
            'user_sent_to_debt_collector_sent_at' => null,
            'customer_sent_to_debt_collector_sent_at' => null,
            'paid_via_debt_collector_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($invoice['status'] === InvoiceStatus::Drafted) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
        }

        if ($invoice['status'] === InvoiceStatus::Sent) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
        }

        if ($invoice['status'] === InvoiceStatus::Paid) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
        }

        if ($invoice['status'] === InvoiceStatus::PaymentOverdue) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
        }

        if ($invoice['status'] === InvoiceStatus::FirstReminderSent) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
            $reminder['customer_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['user_first_reminder_sent_at'] = $invoice['due_date'];
        }

        if ($invoice['status'] === InvoiceStatus::FirstReminderOverdue) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
            $reminder['customer_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['user_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['second_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
        }

        if ($invoice['status'] === InvoiceStatus::SecondReminderSent) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
            $reminder['customer_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['user_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['second_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['customer_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['user_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
        }

        if ($invoice['status'] === InvoiceStatus::SecondReminderOverdue) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
            $reminder['customer_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['user_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['second_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['customer_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['user_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['third_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
        }

        if ($invoice['status'] === InvoiceStatus::ThirdReminderSent) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
            $reminder['customer_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['user_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['second_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['customer_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['user_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['third_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['customer_third_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['user_third_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
        }

        if ($invoice['status'] === InvoiceStatus::ThirdReminderOverdue) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
            $reminder['customer_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['user_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['second_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['customer_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['user_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['third_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['customer_third_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['user_third_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['sent_to_debt_collector_at'] = $invoice['due_date']->addDays($invoiceSetting['debt_collector_reminder_days']);
        }

        if ($invoice['status'] === InvoiceStatus::DebtCollectorSent) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
            $reminder['customer_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['user_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['second_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['customer_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['user_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['third_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['customer_third_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['user_third_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['sent_to_debt_collector_at'] = $invoice['due_date']->addDays($invoiceSetting['debt_collector_reminder_days']);
            $reminder['customer_sent_to_debt_collector_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['debt_collector_reminder_days']);
            $reminder['user_sent_to_debt_collector_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['debt_collector_reminder_days']);
        }

        if ($invoice['status'] === InvoiceStatus::PaidViaDebtCollector) {
            $reminder['first_reminder_at'] = $invoice['due_date'];
            $reminder['customer_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['user_first_reminder_sent_at'] = $invoice['due_date'];
            $reminder['second_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['customer_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['user_second_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['second_reminder_days']);
            $reminder['third_reminder_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['customer_third_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['user_third_reminder_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['third_reminder_days']);
            $reminder['sent_to_debt_collector_at'] = $invoice['due_date']->addDays($invoiceSetting['debt_collector_reminder_days']);
            $reminder['customer_sent_to_debt_collector_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['debt_collector_reminder_days']);
            $reminder['user_sent_to_debt_collector_sent_at'] = $invoice['due_date']->addDays($invoiceSetting['debt_collector_reminder_days']);
            $reminder['paid_via_debt_collector_at'] = $invoice['due_date']->addDays($invoiceSetting['debt_collector_reminder_days'] + 2);
        }

        return $reminder;
    }
}
