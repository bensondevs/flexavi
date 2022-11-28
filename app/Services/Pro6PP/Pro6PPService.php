<?php

namespace App\Services\Pro6PP;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Pro6PPService
{
    /**
     * Pro6PP Response
     *
     * @var object
     */
    public $responseService;
    /**
     * Pro6PP Response Code
     *
     * @var integer
     */
    public $responseServiceCode;
    /**
     * Pro6PP Url
     *
     * @var string
     */
    private $baseUrl;
    /**
     * Pro6PP Auth key
     *
     * @var string
     */
    private $authKey;

    /**
     * Service constructor method
     *
     * @return void
     */

    public function __construct()
    {
        $this->baseUrl = config('pro6pp.base_url');
        $this->authKey = config('pro6pp.auth_key');
    }

    /**
     *  Autocomplete address
     * @param array $data
     *
     * @return object
     */
    public function autocomplete(array $data): object
    {
        $url = $this->baseUrl . 'autocomplete/nl';

        $response = Http::get($url, [
            'authKey' => $this->authKey,
            'postalCode' => $data['zipcode'],
            'streetNumber' => $data['house_number']
        ]);

        $this->setStatusCode($response);

        $result = $this->prepareResponse($response);

        $this->responseService = $result;

        return $result;
    }

    /**
     *  Set status code from response
     *
     * @param $response
     * @return void
     */
    public function setStatusCode($response): void
    {
        $this->responseServiceCode = $response->getStatusCode();
    }

    /**
     *  Prepare Response
     * @param Response $response
     *
     * @return object
     */
    public function prepareResponse($response): object
    {
        return json_decode($response->getBody()->getContents());
    }
}
