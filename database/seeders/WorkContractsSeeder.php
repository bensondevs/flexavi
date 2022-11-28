<?php

namespace Database\Seeders;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Enums\Setting\WorkContract\WorkContractSignatureType;
use App\Enums\WorkContract\WorkContractStatus;
use App\Models\Company\Company;
use App\Models\Customer\Customer;
use App\Models\WorkContract\WorkContract;
use App\Models\WorkContract\WorkContractContent;
use App\Models\WorkContract\WorkContractService;
use App\Models\WorkContract\WorkContractSignature;
use App\Models\WorkService\WorkService;
use Exception;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class WorkContractsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        WorkContract::whereNotNull('id')->forceDelete();
        $faker = Factory::create();
        $companies = Company::has('customers')->get();
        $rawWorkContracts = [];
        $rawWorkContractServices = [];
        $rawWorkContractContents = [];
        $rawWorkContractSignatures = [];
        foreach ($companies as $company) {
            for ($i = 0; $i < 5; $i++) {
                $workContractId = generateUuid();
                $customer = Customer::inRandomOrder()
                    ->where('company_id', $company->id)
                    ->first();

                $rawWorkContracts[] = [
                    'id' => $workContractId,
                    'company_id' => $company->id,
                    'customer_id' => $customer->id,
                    'footer' => '<COMPANY_ADDRESS><COMPANY_HOUSE_NUMBER>  -  <COMPANY_ZIPCODE>  -  <COMPANY_CITY>  -  Btw <COMPANY_VAT_NUMBER> -  <COMPANY_PHONE_NUMBER> - <COMPANY_PHONE_NUMBER>   -  <COMPANY_WEBSITE> - <COMPANY_EMAIL>',
                    'number' => 'WCT-' . random_int(100, 999999) . random_int(100, 999999),
                    'amount' => rand(100, 300),
                    'discount_amount' => rand(10, 30),
                    'potential_amount' => $faker->randomElement([0, rand(100, 300)]),
                    'total_amount' => rand(100, 300),
                    'status' => WorkContractStatus::getRandomValue(),
                    'created_at' => now()->subDays(rand(5, 20)),
                    'updated_at' => now()->subDays(rand(5, 20)),
                ];

                $rawWorkContractContents = array_merge($rawWorkContractContents, $this->forewordContents($workContractId));
                $rawWorkContractContents = array_merge($rawWorkContractContents, $this->contractContents($workContractId));

                $rawWorkContractSignatures[] = [
                    'id' => generateUuid(),
                    'work_contract_id' => $workContractId,
                    'name' => $faker->name,
                    'type' => WorkContractSignatureType::Customer,
                    'created_at' => now()->subDays(rand(5, 20)),
                    'updated_at' => now()->subDays(rand(5, 20)),
                ];
                $rawWorkContractSignatures[] = [
                    'id' => generateUuid(),
                    'work_contract_id' => $workContractId,
                    'name' => $faker->name,
                    'type' => WorkContractSignatureType::Roofer,
                    'created_at' => now()->subDays(rand(5, 20)),
                    'updated_at' => now()->subDays(rand(5, 20)),
                ];

                for ($j = 0; $j < rand(1, 3); $j++) {
                    $workService = WorkService::inRandomOrder()->first();
                    $rawWorkContractServices[] = [
                        'id' => generateUuid(),
                        'work_contract_id' => $workContractId,
                        'work_service_id' => $workService->id,
                        'amount' => rand(1, 3),
                        'unit_price' => $workService->price,
                        'tax_percentage' => $workService->tax_percentage,
                        'total' => $workService->price * rand(1, 3),
                        'created_at' => now()->subDays(rand(5, 20)),
                        'updated_at' => now()->subDays(rand(5, 20)),
                    ];
                }
            }
        }

        foreach (array_chunk($rawWorkContracts, 100) as $rawWorkContractsChunk) {
            WorkContract::insert($rawWorkContractsChunk);
        }

        foreach (array_chunk($rawWorkContractServices, 100) as $rawWorkContractServicesChunk) {
            WorkContractService::insert($rawWorkContractServicesChunk);
        }

        foreach (array_chunk($rawWorkContractSignatures, 100) as $rawWorkContractSignaturesChunk) {
            WorkContractSignature::insert($rawWorkContractSignaturesChunk);
        }

        foreach (array_chunk($rawWorkContractContents, 400) as $rawWorkContractContentsChunk) {
            WorkContractContent::insert($rawWorkContractContentsChunk);
        }

        foreach (WorkContractSignature::all() as $setting) {
            $setting->addMedia(
                UploadedFile::fake()
                    ->image('image.png', 100, 100)
                    ->size(100)
            )->preservingOriginal()
                ->toMediaCollection('signature');
        }

        foreach (WorkContract::all() as $workContract) {
            $workContract->countWorksAmount();
        }
    }

    /**
     * Prepare work contract foreword content.
     *
     * @param string $workContractId
     * @return array
     */
    public function forewordContents(string $workContractId): array
    {
        return [
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 1,
                'text' => 'Ondertekenende:',
                'position_type' => WorkContractContentPositionType::Foreword,
                'text_type' => WorkContractContentTextType::Title,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 2,
                'text' => '<COMPANY_NAME> gevestigd te <COMPANY_CITY>, <COMPANY_ADDRESS> <COMPANY_HOUSE_NUMBER>, <COMPANY_ZIPCODE> Hierna te noemen opdrachtnemer,',
                'position_type' => WorkContractContentPositionType::Foreword,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 3,
                'text' => 'Naam: Fam. <CUSTOMER_FULLNAME> te <CUSTOMER_CITY> Straat: <CUSTOMER_ADDRESS> Postcode: <CUSTOMER_ZIPCODE> Tel <CUSTOMER_PHONE> Hier na te noemen opdrachtgever,',
                'position_type' => WorkContractContentPositionType::Foreword,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 4,
                'text' => 'Komen overeen dat de navolgende werkzaamheden voor een totaal bedrag van  €  <TOTAL_AMOUNT_EXCLUDING_TAX> ex. 21% Btw  aan de woning van de opdrachtgever door opdrachtnemer en zijn personeelsleden zullen worden verricht.',
                'position_type' => WorkContractContentPositionType::Foreword,
                'text_type' => WorkContractContentTextType::SubPoint,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }

    /**
     * Prepare work contract content.
     *
     * @param string $workContractId
     * @return array[]
     */
    public function contractContents(string $workContractId): array
    {
        return [
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 1,
                'text' => 'De navolgende werkzaamheden en leveringen zijn niet in onze prijs opgenomen, wij gaan er van uit dat u deze verzorgt. ',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Title,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 2,
                'text' => 'Ter beschikking stellen stroomvoorziening 230 volt 16 amp. ',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 3,
                'text' => 'Sanitaire voorzieningen.',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 4,
                'text' => 'Oponthoud door werkzaamheden van derden worden doorberekend a € 49.50 p/u, per man',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 5,
                'text' => 'Bereikbaarheid werkplek met materieel',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],


            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 6,
                'text' => 'Bovenstaande prijzen zijn exclusief',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Title,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 7,
                'text' => 'Werkzaamheden die niet genoemd zijn vallen buiten deze offerte',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 8,
                'text' => 'Werkzaamheden aan asbesthoudende materialen',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 9,
                'text' => 'Werkzaamheden anders dan omschreven',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 10,
                'text' => 'Parkeerkosten',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],


            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 11,
                'text' => 'Algemeen',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Title,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 12,
                'text' => 'onvoorziene werkzaamheden, benodigd ter uitvoering van de originele werkzaamheden, zullen uitsluitend, tegen een overeengekomen meerprijs, door ons uitgevoerd worden',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 13,
                'text' => 'Ziet af van de wettelijke bedenktijd van 14 dagen',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Point,
                'created_at' => now(),
                'updated_at' => now(),
            ],


            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 14,
                'text' => 'Opdrachtnemer is vrij de werkzaamheden naar eigen inzicht in te richten en uit te voeren. Opdrachtnemer zal met de uitvoering van de werkzaamheden aanvangen op de datum <DATE>',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Title,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 15,
                'text' => 'Opdrachtnemer ontvangt van opdrachtgever bij de oplevering van de werkzaamheden een  betaling van € <TOTAL_AMOUNT_EXCLUDING_TAX> ex. <FORMATTED_TAX_PERCENTAGE> btw, inclusief materiaal en arbeid.',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Title,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 16,
                'text' => 'Op deze overeenkomst van opdracht zijn de Algemene Voorwaarden van <COMPANY_NAME>  van toepassing, welke u terug kan vinden op onze website, <COMPANY_WEBSITE>, (deze zijn ook ter hand gereikt) daarvan ook onlosmakelijk deel uitmaken. Indien werkzaamheden zien op het herstellen van lekkages, dan geldt voor wat betreft het herstel daarvan expliciet dat er sprake is van een inspanningsverplichting en niet van een resultaatverplichting.',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Title,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => generateUuid(),
                'work_contract_id' => $workContractId,
                'order_index' => 17,
                'text' => 'Aldus overeengekomen en in tweevoud ondertekend te <CUSTOMER_CITY> op datum <CURRENT_DATE>',
                'position_type' => WorkContractContentPositionType::Contract,
                'text_type' => WorkContractContentTextType::Title,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
    }
}
