<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Webpatser\Uuid\Uuid;

function searchInCollection(Collection $collection, $search)
{
    return ($collection->filter(function ($item) use ($search) {
        $attributes = array_keys($item);
        foreach ($attributes as $attribute)
            if (isset($item[$attribute]) && (! is_array($item[$attribute])))
                if (stripos($item[$attribute], $search) !== false)
                    return true;

        return false;
    }))->toArray();
}

function urlToUsername($urlString)
{
    $urlString = str_replace('http://', '', $urlString);
    $urlString = str_replace('https://', '', $urlString);
    $urlString = str_replace('www.', '', $urlString);

    $clearParams = explode('/', $urlString);
    
    $mainDomain = $clearParams[0];
    $breakMainDomain = explode('.', $mainDomain);
    $domainName = $breakMainDomain[0];
    $domainExtension = $breakMainDomain[1];

    return $domainName . $domainExtension;
}

function last_character(string $string)
{
    return substr($string, -1);
}

function str_to_singular(string $string)
{
    return Str::singular($string);
}

function numbertofloat($number)
{
    return sprintf('%.2f', $number);
}

function generateUuid()
{
    return Uuid::generate()->string;
}

function db($table = null)
{
    return ($table) ? 
        DB::table($table) :
        new DB;
}

function hashCheck($check, $hashed)
{
    return Hash::check($check, $hashed);
}

function encryptArray(array $array)
{
    return encryptString(json_encode($array));
}

function decryptArray(string $arrayString)
{
    return json_decode(decryptString($arrayString), true);
}

function encryptString($string)
{
    return Crypt::encryptString($string);
}

function decryptString($encrypted)
{
    return Crypt::decryptString($encrypted);
}

function executor()
{
    return auth()->check() ?
        auth()->user()->id :
        'SYSTEM';
}

function strtobool($string = null)
{   
    if ($string === null) return false;

    if ($string == 'true' || $string == 'false') {
        return filter_var($string, FILTER_VALIDATE_BOOLEAN);
    }

    return true;
}

function random_string($length = 4)
{
    return randomString($length);
}

function randomString($length = 8)
{
    return Str::random($length);
}

function randomDate($format = 'd/m/Y')
{
    $date = carbon()
        ->now()
        ->addDays(rand((-5), 5))
        ->format($format);

    return $date;
}

function random_phone($length = 12)
{
    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= random_int(0, 9);
    }

    return $result;
}

function carbon($parameter = null)
{
    return $parameter ? 
        new Carbon() : new Carbon($parameter);
}

function carbonParseFormat($dateString, $format)
{
    return carbon()
        ->parse($dateString)
        ->format($format);
}

function carbonParseTimestamp($dateString)
{
    return carbon()
        ->parse($dateString)
        ->timestamp;
}

function currentTimestamp()
{
    return carbon()->now()->timestamp;
}

function carbonStartOfDay($date)
{
    return Carbon::parse($date)->copy()->startOfDay();
}

function carbonEndOfDay($date)
{
    return Carbon::parse($date)->copy()->endOfDay();
}

function current_date()
{
    return now()->copy()->startOfDay()->toDateString();
}

function month_start_date()
{
    return now()->copy()->startOfMonth()->toDateString();
}

function gate()
{
    return new Gate();
}

function jsonResponse($response)
{
    return response()->json($response);
}

function viewResponse($viewName, $response, $repositoryObject)
{
    $view = view($viewName, $response);
    $view = $repositoryObject ?
        $view->with(
            $repositoryObject->status, 
            $repositoryObject->message
        ) : $view;

    return $view;
}

function apiResponse($repositoryObject, $responseData = null)
{
    $response = [];

    if (is_array($responseData)) {
        $attribute = array_keys($responseData)[0];
        $response[$attribute] = $responseData[$attribute];
    } else if ($responseData) {
        $response['data'] = $responseData;
    }
    
    if ($repositoryObject->status)
        $response['status'] = $repositoryObject->status;
    if ($repositoryObject->message)
        $response['message'] = $repositoryObject->message;
    if ($repositoryObject->queryError)
        $response['query_error'] = $repositoryObject->queryError;

    return response()->json(
        $response, 
        $repositoryObject->httpStatus
    );
}

function uppercaseArray($array)
{
    return array_map('strtoupper', $array);
}

function flashMessage($repositoryObject)
{
    session()->flash(
        $repositoryObject->status, 
        ($repositoryObject->status == 'success') ? 
            $repositoryObject->message : 
            $repositoryObject->queryError
    );
}

function uploadFile($fileRequest, string $directory)
{
    if (last_character($directory) !== '/') {
        $directory = ($directory . '/');
    }

    $storageFile = new \App\Repositories\StorageFileRepository;
    if (is_base64_string($fileRequest)) {
        $filePath = uploadBase64Image($fileRequest, $directory);
        return $storageFile->record($filePath);
    }

    $path = $directory . Carbon::now()->format('YmdHis');
    $path .= urlencode($fileRequest->getClientOriginalName());
    $fileContent = file_get_contents($fileRequest->getRealPath());

    return $storageFile->upload($fileContent, $path);
}

function uploadBase64File($base64File, $path = 'uploads/documents', $fileName = '')
{
    if(! File::exists($path))
        File::makeDirectory($path, $mode = 0755, true, true);

    $base64String = substr($base64File, strpos($base64File, ",") + 1);
    $fileData = base64_decode($base64String);
    $extension = explode('/', explode(':', substr($base64File, 0, strpos($base64File, ';')))[1])[1];

    // Prepare image path
    $path = (substr($path, -1) == '/') ?
        $path : 
        $path . '/';
    $fileName = ($fileName ? $fileName : Carbon::now()->format('YmdHis')) . '.' . $extension;
    $filePath = $path . $fileName;
    $putImage = Storage::put($filePath, $fileData);

    return $putImage ? $filePath : false;
}

function uploadBase64Image($base64Image, $imagePath = 'uploads/test', $imageName = '')
{
    if(! File::exists($imagePath))
        File::makeDirectory($imagePath, $mode = 0755, true, true);

    $base64String = substr($base64Image, strpos($base64Image, ",") + 1);
    $imageData = base64_decode($base64String);
    $extension = explode('/', explode(':', substr($base64Image, 0, strpos($base64Image, ';')))[1])[1];

    // Prepare image path
    $imagePath = (substr($imagePath, -1) == '/') ?
        $imagePath : 
        $imagePath . '/';
    $fileName = ($imageName ? $imageName : Carbon::now()->format('YmdHis')) . '.' . $extension;
    $filePath = $imagePath . $fileName;

    $putImage = Storage::put($filePath, $imageData);

    return $putImage ? $filePath : false;
}

function deleteFile($filePath)
{
    return Storage::delete($filePath);
}

function toRupiah($amount)
{
    $rupiah = (string) number_format($amount, 0, ',', '.');

    return 'Rp. ' . $rupiah;
}

function formatRupiah($amount)
{
    return toRupiah($amount);
}

function currency_format($amount, string $currencyCode = 'EUR', string $locale = 'nl_NL.UTF-8')
{
    $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
    return $formatter->formatCurrency($amount, $currencyCode);
}

function currentLink()
{
    return url()->current();
}

function requestMethod()
{
    return request()->method();
}

function isRoute($routeName)
{
    return Route::currentRouteName() == $routeName;
}

function is_base64_string($string)
{
    $temporary = explode(';base64,', $string);
    if (isset($temporary[1])) {
        $string = $temporary[1];
    }

    return base64_encode(base64_decode($string, true)) === $string;
}

function base64_extension($string) 
{
    if (! is_base64_string($string)) {
        return false;
    }

    $temporary = explode(';base64,', $string);
    $fileType = $temporary[0];
    $dataType = explode('data:', $fileType);
    if (! isset($dataType[1])) {
        return null;
    }

    $dataType = $dataType[1];
    $temporaryDataType = explode('/', $dataType);
    if (! isset($temporaryDataType[1])) {
        return null;
    }

    return $temporaryDataType[1];
}

function isRouteStartsWith($start, $routeName = '')
{
    // if route is not defined make it current route
    $routeName = $routeName ? $routeName : Route::currentRouteName();

    return substr($routeName, 0, strlen($start)) == $start;
}

function convertBase64ToUploadedFile($base64String)
{
    $fileData = base64_decode($base64String);
    
    $mimeType = explode(':', substr($base64String, 0, strpos($base64String, ';')))[1];

    $tmpFilePath = sys_get_temp_dir() . '/' . random_string(20);
    file_put_contents($tmpFilePath, $fileData);

    $tmpFile = new SymfonyFile($tmpFilePath);
    $file = new UploadedFile(
        $tmpFile->getPathname(),
        random_string(10),
        $mimeType,
        0,
        false
    );

    return $file;
}

function createPagination($collections, $perPage = 10, $currentPage = 1)
{
    $pagination = new App\Repositories\PaginationRepository;

    return $pagination->paginateCollection(
        $collections, 
        $perPage, 
        $currentPage
    );
}

function generatePaginationLinks(
    $currentLink,
    array $urlParameters,
    $amountOfPage,
    $currentPage = 1
) {
    $link = [
        'prev_link' => '#',
        'next_link' => '#',
        'current_link' => '#',
        'urls' => [],
    ];
    $currentPage = isset($urlParameters['page']) ?
        $urlParameters['page'] : 1;
    $urlParameters['page'] = isset($urlParameters['page']) ?
        $urlParameters['page'] : $currentLink . '?page=' . $currentPage;

    for ($i = 1; $i <= $amountOfPage; $i++) {
        $iteration = 0;
        $amountOfParams = count($urlParameters);

        $link['urls'][$i] = $currentLink . '?';
        foreach ($urlParameters as $key => $parameter) {
            if ($key != 'page')
                $link['urls'][$i] .= $key . '=' . $parameter;
            else
                $link['urls'][$i] .= 'page' . '=' . $i;

            $iteration++;
            $link['urls'][$i] = $iteration != $amountOfParams ?
                $link['urls'][$i] . '&' : $link['urls'][$i];
        }

        if ($i == ($currentPage - 1))
            $link['prev_link'] = $link['urls'][$i];
        else if ($i == ($currentPage + 1))
            $link['next_link'] = $link['urls'][$i];
        else if ($i == ($currentPage))
            $link['current_link'] = $link['urls'][$i];
    }

    return $link;
}

function queueSendEmail($job, $delay = 1)
{
    $job->delay(carbon()->now()->addSeconds($delay));
    dispatch($job);
}

function queueJob($job, $delay = 1)
{
    $job->delay(carbon()->now()->addSeconds($delay));
    dispatch($job);
}
