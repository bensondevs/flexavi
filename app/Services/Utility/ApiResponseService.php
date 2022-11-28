<?php

namespace App\Services\Utility;

use Illuminate\Http\JsonResponse;

class ApiResponseService
{
    /**
     * Status of the response container property.
     *
     * @var int|null
     */
    private $statusCode = 200;

    /**
     * Message of the API request response container property.
     *
     * @var string
     */
    private $message;

    /**
     * Additional extra-properties of the response container.
     *
     * @var array
     */
    private $extras = [];

	/**
	 * Create New Service Instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

    /**
     * Get status code of the API response.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set status code of the API response.
     *
     * @param int $code
     * @return self
     */
    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;

        return $this;
    }

    /**
     * Get message of the API response.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set message of the API response.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Quick set the class properties instances.
     *
     * @param int $statusCode
     * @param string $message
     * @param array $extras
     * @return $this
     */
    public function quickSet(
        int $statusCode = 200,
        string $message = '',
        array $extras = []
    ): self
    {
        $this->setStatusCode($statusCode);
        $this->setMessage($message);
        $this->extras = $extras;

        return $this;
    }

    /**
     * Get JSON properties for the API response.
     *
     * @return array
     */
    public function getJsonProperties(): array
    {
        return array_merge([
            'status' => ($status = $this->getStatusCode()),
            'message' => $this->getMessage(),
        ], $this->extras);
    }

    /**
     * Return response json for the controller.
     *
     * @return JsonResponse
     */
    public function response(): JsonResponse
    {
        $properties = $this->getJsonProperties();
        $statusCode = $this->getStatusCode();

        return response()->json($properties, $statusCode);
    }
}
