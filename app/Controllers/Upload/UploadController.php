<?php

namespace App\Controllers\Upload;

use App\Files\FileStore;
use App\Controllers\Controller;

class UploadController extends Controller
{
	
	public function store($request, $response)
	{
		$user = $this->auth->requestUser($request);
		
		if (! $user) {
			return $response->withStatus(401)->withJson(['user' => 'Authorized']);
		}

		if (! $file = $request->getUploadedFiles()['file'] ?? null) {
			return $response->withJson(['error' => 'error occured'])->withStatus(422);
		}

		$file = (new FileStore($file, $this))->store();

		if (! $file) {
			return $response->withJson(['error' => $file->getError()])->withStatus(422);
		}

		if (!$stored = $this->image->make(uploads_path($file->getStored()->uuid))) {
			return $response->withJson(['error' => $file->getError()])->withStatus(422);
		}

		$fullfilepath = 'storage/uploads/' . $stored->filename . '.' . explode('/', $stored->mime)[1];

	  return $response->withJson(['file' => $fullfilepath]);
	}
}