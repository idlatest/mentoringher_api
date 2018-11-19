<?php

namespace App\Files;

use App\Models\Image;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Slim\Http\UploadedFile;

class FileStore
{
	protected $file = null;
	protected $stored = null;
	protected $container;
	protected $error;

	public function __construct(UploadedFile $file, $container)
	{
		$this->file = $file;
		$this->container = $container;
	}

	public function getStored()
	{
		return $this->stored;
	}

	public function store()
	{
		$basePath = $_SERVER['DOCUMENT_ROOT'] . '/storage/uploads';
		$fileName = $this->file->getClientFilename();
    $fileType = $this->file->getClientMediaType();

    $fileNewName = uniqid(bin2hex(random_bytes(16)) . date('Ydm') . '-') . $fileName;

    $extensions = ['image/jpeg', 'image/png', 'audio/mp3', 'audio/ogg'];

    if (! $this->container->image->make($this->file->file) || ! in_array($fileType, $extensions)) {
			$this->error = 'Invalid file type';
			return false;
		}

		if ($this->file->getSize() > 10000000) {
			$this->error = 'File greater than 10mb';
			return false;
		}

		try {
			$model = $this->createModel();
			// $path = $model->uuid . '.' . explode('/', $fileType)[1];
			$this->file->moveTo(uploads_path($model->uuid));
		} catch (UnsatisfiedDependencyException $e) {
			$this->error = $e->getMessage();
		}

		return $this;
	}

	public function createModel()
	{
		return $this->stored = Image::create();
	}

	public function getError()
	{
		return $this->error;
	}
}