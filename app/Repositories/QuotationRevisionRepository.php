<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Quotation;
use App\Models\QuotationRevision;

class QuotationRevisionRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new QuotationRevision);
	}

	public function save(array $revisionData = [])
	{
		try {
			$revision = $this->getModel();
			$revision->fill($revisionData);
			$revision->save();

			$this->setModel($revision);

			$this->setSuccess('Successfully save revision.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save revision', $error);
		}

		return $this->getModel();
	}

	public function apply()
	{
		try {
			$revision = $this->getModel();
			
			// Apply revision
			$quotation = $revision->quotation;
			foreach ($revision->revisions as $attribute => $revision) {
				$quotation->{$attribute} = $revision;
			}
			$quotation->save();

			$revision->is_applied = true;
			$revision->applied_at = carbon()->now();
			$revision->save();

			$this->setModel($revision);

			$this->setSuccess('Successfully apply revision to quotation.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to apply revision.', $error);
		}

		return $this->getModel();
	}

	public function delete(bool $force = false)
	{
		try {
			$revision = $this->getModel();
			$force ? $revision->forceDelete() : $revision->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete revision.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete revision.', $error);
		}

		return $this->returnResponse();
	}
}
