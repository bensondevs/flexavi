<?php

namespace App\Traits;

trait RepositoryResponse
{
    /**
     * Http default status response
     *
     * @var int
     */
    public $httpStatus = 200;

    /**
     * Explicit string of status
     *
     * @var string|null
     */
    public $status;

    /**
     * Multiple explicit method calling statuses
     *
     * @var array
     */
    public $statuses = [];

    /**
     * Message as response to client
     *
     * @var string|null
     */
    public $message;

    /**
     * Collection of messages as responses to client
     *
     * @var array
     */
    public $messages = [];

    /**
     * Throwable error message container
     *
     * @var string|null
     */
    public $queryError;

    /**
     * Collection of throwable error message container
     *
     * @var array
     */
    public $queryErrors = [];

    /**
     * Flash response status and message
     *
     * @return void
     */
    public function flash()
    {
        session()->flash($this->status, $this->message);
    }

    /**
     * Set unprocessed input of the execution of the repository action
     *
     * @param string $message
     * @return void
     */
    public function setUnprocessedInput(string $message = 'Wrong input'): void
    {
        $this->setResponseStatus('error');
        $this->setResponseMessage($message);
        $this->setHttpStatusCode(422);
    }

    /**
     * Set response status of the repository
     *
     * @param string $status
     * @return void
     */
    public function setResponseStatus(string $status = 'error')
    {
        $this->status = $status;
        $this->statuses[] = $status;
    }

    /**
     * Set response message for the client
     *
     * @param string $message
     * @return void
     */
    public function setResponseMessage(string $message = 'Unknown')
    {
        $this->message = $message;
        $this->messages[] = $message;
    }

    /**
     * Set http status code
     *
     * @param int $code
     * @return int
     */
    public function setHttpStatusCode(int $code)
    {
        return $this->httpStatus = $code;
    }

    /**
     * Set forbidden response of the execution of repository action
     *
     * @param string $message
     * @return void
     */
    public function setForbidden(string $message = 'Forbidden')
    {
        $this->setResponseStatus('error');
        $this->setResponseMessage($message);
        $this->setHttpStatusCode(403);
    }

    /**
     * Set not found response of the execution of repository action
     *
     * @param string $message
     * @return void
     */
    public function setNotFound(string $message = 'Not found')
    {
        $this->setResponseStatus('error');
        $this->setResponseMessage($message);
        $this->setHttpStatusCode(404);
    }

    /**
     * Set success response of the execution of repository action
     *
     * @param string $message
     * @return void
     */
    public function setSuccess(string $message = 'Success')
    {
        $this->setResponseStatus('success');
        $this->setResponseMessage($message);
        switch (request()->method()) {
            case 'POST':
                $this->setHttpStatusCode(201);
                break;
            default:
                $this->setHttpStatusCode(200);
                break;
        }
    }

    /**
     * Set error response of the execution of repository action
     *
     * @param string $message
     * @param string $queryError
     * @return void
     */
    public function setError(string $message = 'Error', string $queryError = '')
    {
        $this->setResponseStatus('error');
        $this->setResponseMessage($message);
        $this->setHttpStatusCode(500);
        $this->setQueryError($queryError);
    }

    /**
     * Set reponse of query error for the client
     *
     * @param string $queryError
     * @return void
     */
    public function setQueryError(string $queryError = 'Unknown')
    {
        $this->queryError = $queryError;
        $this->queryErrors[] = $queryError;
    }

    /**
     * Set custom error response of the execution of repository action
     *
     * @param string $message
     * @param int $errorCode
     * @param string $queryError
     * @return void
     */
    public function setCustomError(
        string $message,
        int    $errorCode,
        string $queryError = ''
    )
    {
        $this->setResponseStatus('error');
        $this->setResponseMessage($message);
        $this->setHttpStatusCode($errorCode);
        $this->setQueryError($queryError);
    }

    /**
     * Give return response of data or just boolean that represents
     * the execution status of the repository
     *
     * @param bool $data
     * @return bool
     */
    public function returnResponse(bool $data = true)
    {
        return $this->status !== 'success' ?: $data;
    }
}
