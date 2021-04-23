<?php

declare(strict_types=1);

namespace App\Repositories\Base;

use Illuminate\Http\Response;

trait RepositoryPayload 
{
	public $status;
	public $httpStatus = 200;
	public $message;
	public $queryError;

	public function flash()
	{
		session()->flash($this->status, $this->message);
	}

	public function setUnprocessedInput($message = 'Wrong input')
	{
		$this->status = 'error';
		$this->message = $message;
		$this->httpStatus = 422;

		return null;
	}

	public function setForbidden($message = 'Forbidden')
	{
		$this->status = 'error';
		$this->message = $message;
		$this->httpStatus = 403;

		return null;
	}

	public function setNotFound($message = 'Not found')
	{
		$this->status = 'error';
		$this->message = $message;
		$this->httpStatus = 404;

		return null;
	}

	public function setSuccess($message = 'Success')
	{
		$this->status = 'success';
		$this->httpStatus = 200;
		$this->message = $message;

		return null;
	}

	public function setError($message = 'Error', $queryError)
	{
		$this->status = 'error';
		$this->message = $message;
		$this->httpStatus = 500;
		$this->queryError = $queryError;

		return null;
	}

	public function setCustomError($message, $errorCode)
	{
		$this->status = 'error';
		$this->message = $message;
		$this->httpStatus = $errorCode;
		$this->queryError = null;

		return null;
	}

	public function returnResponse($data = true)
	{
		return ($this->status == 'success') ?
			$data : false;
	}
}