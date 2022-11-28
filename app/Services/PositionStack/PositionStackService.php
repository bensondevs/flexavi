<?php

namespace App\Services\PositionStack;

use App\Models\Address\Address;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PositionStackService
{
    /**
     * Position Stack Url
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Position Stack Access key
     *
     * @var string
     */
    private $accessKey;

    /**
     * Service constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->baseUrl = config('positionstack.base_url');
        $this->accessKey = config('positionstack.access_key');
    }

    /**
     * Forward geocoding is the process of converting a free-text address or place to location data.
     *
     * @param Address $address
     * @return object
     */
    public function forward(Address $address)
    {
        $url = $this->baseUrl . 'forward';
        $accessKey = $this->accessKey;
        $query =
            $address->address .
            $address->house_number .
            ', ' .
            $address->city .
            ', ' .
            $address->province .
            ', ' .
            $address->zipcode;

        $response = Http::get($url, [
            'access_key' => $accessKey,
            'query' => $query,
            'region' => $address->city,
            'limit' => 1,
        ]);

        $response = $this->prepareResponse($response);

        if ($response && isset($response->data) && count($response->data)) {
            $response = $response->data[0];
            if ($response) {
                $address->latitude = $response->latitude;
                $address->longitude = $response->longitude;
                $address->saveQuietly();
            }
        }
    }

    /**
     *  Prepare Response
     * @param Response $response
     *
     * @return object
     */
    public function prepareResponse(Response $response): object
    {
        return json_decode($response->getBody()->getContents());
    }
}
