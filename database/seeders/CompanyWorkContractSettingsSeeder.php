<?php

namespace Database\Seeders;

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Models\Company\Company;
use App\Models\Setting\WorkContractContentSetting;
use App\Models\Setting\WorkContractSetting;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CompanyWorkContractSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        WorkContractSetting::whereNotNull('id')->delete();
        $companies = Company::all();
        $rawSettings = [];
        foreach ($companies as $company) {
            $settingId = generateUuid();


            WorkContractSetting::insert([
                [
                    'id' => $settingId,
                    'company_id' => $company->id,
                    'footer' => '<COMPANY_ADDRESS><COMPANY_HOUSE_NUMBER>  -  <COMPANY_ZIPCODE>  -  <COMPANY_CITY>  -  Btw <COMPANY_VAT_NUMBER> -  <COMPANY_PHONE_NUMBER> - <COMPANY_PHONE_NUMBER>   -  <COMPANY_WEBSITE> - <COMPANY_EMAIL>',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            $rawForewordContents = [
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Ondertekenende:',
                    'position_type' => WorkContractContentPositionType::Foreword,
                    'text_type' => WorkContractContentTextType::Title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => '<COMPANY_NAME> gevestigd te <COMPANY_CITY>, <COMPANY_ADDRESS> <COMPANY_HOUSE_NUMBER>, <COMPANY_ZIPCODE> Hierna te noemen opdrachtnemer,',
                    'position_type' => WorkContractContentPositionType::Foreword,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Naam: Fam. <CUSTOMER_FULLNAME> te <CUSTOMER_CITY> Straat: <CUSTOMER_ADDRESS> Postcode: <CUSTOMER_ZIPCODE> Tel <CUSTOMER_PHONE> Hier na te noemen opdrachtgever,',
                    'position_type' => WorkContractContentPositionType::Foreword,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Komen overeen dat de navolgende werkzaamheden voor een totaal bedrag van  €  <TOTAL_AMOUNT_EXCLUDING_TAX> ex. 21% Btw  aan de woning van de opdrachtgever door opdrachtnemer en zijn personeelsleden zullen worden verricht.',
                    'position_type' => WorkContractContentPositionType::Foreword,
                    'text_type' => WorkContractContentTextType::SubPoint,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            foreach ($rawForewordContents as $index => $rawForewordContent) {
                $rawForewordContents[$index]['order_index'] = $index + 1;
            }
            WorkContractContentSetting::insert($rawForewordContents);

            $rawContractContents = [
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'De navolgende werkzaamheden en leveringen zijn niet in onze prijs opgenomen, wij gaan er van uit dat u deze verzorgt. ',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Ter beschikking stellen stroomvoorziening 230 volt 16 amp. ',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Sanitaire voorzieningen.',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Oponthoud door werkzaamheden van derden worden doorberekend a € 49.50 p/u, per man',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Bereikbaarheid werkplek met materieel',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],


                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Bovenstaande prijzen zijn exclusief',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Werkzaamheden die niet genoemd zijn vallen buiten deze offerte',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Werkzaamheden aan asbesthoudende materialen',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Werkzaamheden anders dan omschreven',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Parkeerkosten',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],


                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Algemeen',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'onvoorziene werkzaamheden, benodigd ter uitvoering van de originele werkzaamheden, zullen uitsluitend, tegen een overeengekomen meerprijs, door ons uitgevoerd worden',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Ziet af van de wettelijke bedenktijd van 14 dagen',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Point,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],


                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Opdrachtnemer is vrij de werkzaamheden naar eigen inzicht in te richten en uit te voeren. Opdrachtnemer zal met de uitvoering van de werkzaamheden aanvangen op de datum <DATE>',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Opdrachtnemer ontvangt van opdrachtgever bij de oplevering van de werkzaamheden een  betaling van € <TOTAL_AMOUNT_EXCLUDING_TAX> ex. <FORMATTED_TAX_PERCENTAGE> btw, inclusief materiaal en arbeid.',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Op deze overeenkomst van opdracht zijn de Algemene Voorwaarden van <COMPANY_NAME>  van toepassing, welke u terug kan vinden op onze website, <COMPANY_WEBSITE>, (deze zijn ook ter hand gereikt) daarvan ook onlosmakelijk deel uitmaken. Indien werkzaamheden zien op het herstellen van lekkages, dan geldt voor wat betreft het herstel daarvan expliciet dat er sprake is van een inspanningsverplichting en niet van een resultaatverplichting.',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => generateUuid(),
                    'work_contract_setting_id' => $settingId,
                    'text' => 'Aldus overeengekomen en in tweevoud ondertekend te <CUSTOMER_CITY> op datum <CURRENT_DATE>',
                    'position_type' => WorkContractContentPositionType::Contract,
                    'text_type' => WorkContractContentTextType::Title,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            foreach ($rawContractContents as $index => $rawContractContent) {
                $rawContractContents[$index]['order_index'] = $index + 1;
            }
            WorkContractContentSetting::insert($rawContractContents);

        }

        foreach (WorkContractSetting::all() as $setting) {
            $setting->addMedia(
                public_path('seeders/signature/signature.png')
            )->preservingOriginal()
                ->usingName($faker->name)
                ->toMediaCollection('signature');
        }
    }
}
