<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\PaymentTerm;

use App\Enums\PaymentTerm\PaymentTermStatus;

class PaymentTermRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new PaymentTerm);
	}

	public function save(array $termData)
	{
		try {
			$term = $this->getModel();
			$term->fill($termData);
			$term->save();

			$this->setModel($term);

			$this->setSuccess('Successfully save payment term.');
		} catch (QueryException $qe) {
			$this->setError('Failed to save payment term.');
		}

		return $this->getModel();
	}

	public function markAsPaid()
	{
		try {
			$term = $this->getModel();
			$term->status = PaymentTermStatus::Paid;
			$term->save();

			$this->setModel($term);

			$this->setSuccess('Payment term has been settled.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to settle payment term.', $error);
		}

		return $this->getModel();
	}

	public function cancelPaidStatus()
	{
		try {
			$term = $this->getModel();
			$term->status = PaymentTermStatus::Unpaid;
			$term->save();

			$this->setModel($term);

			$this->setSuccess('Successfully cancel paid status');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to cancel paid status', $error);
		}

		return $this->getModel();
	}

	public function forwardToDebtCollector()
	{
		try {
			$term = $this->getModel();
			$term->status = PaymentTermStatus::ForwardedToDebtCollector;
			$term->save();

			$this->setModel($term);

			$this->setSuccess('Successfully forward payment term to debt collector.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to forward payment term to debt collector.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$term = $this->getModel();
			$force ? 
				$term->forceDelete() : 
				$term->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete payment term.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete payment term.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
