<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;
use \Illuminate\Support\Facades\Storage;

use App\Models\StorageFile;

use App\Repositories\Base\BaseRepository;

class StorageFileRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new StorageFile);
	}

	public function upload($content, string $path = '', $disk = 'public')
	{
		if (Storage::disk($disk)->put($path, $content)) {
			$file = $this->getModel();
			$file->fill([
				'disk' => $disk,
				'path' => $path,
			]);
			$file->save();

			return $this->record($path, $disk);
		}

		return false;
	}

	public function record(string $path, $disk = 'public')
	{
		try {
			$file = $this->getModel();
			$file->path = $path;
			$file->disk = $disk;
			$file->save();		

			$this->setModel($file);

			$this->setSuccess('Successfully save file.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to save file.', $error);
		}

		return $this->getModel();
	}

	public function delete()
	{
		try {
			$file = $this->getModel();
			if (Storage::exists($file->path)) {
				Storage::delete($file->path);
			}
			$file->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete file.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete file.', $error);
		}
	}
}
