<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Transformers\UserTransformer;
use League\Fractal\Resource\Item;
use Respect\Validation\Validator as V;

class LoginController extends Controller {

	public function login($request, $response) {

		$Validation = $this->validateLoginRequest($request);

		if ($Validation->failed()) {
			return $response->withJson([
				'status' => 422,
				'message' => 'email or password is invalid',
				'data' => [],
			], 422);
		}

		$auth = $this->auth->attempt(
			$request->getParam('email'),
			$request->getParam('password')
		);

		if (!$auth) {
			return $response->withJson([
				'status' => 422,
				'message' => 'email or password is invalid',
				'data' => []
			], 422);
		}

		$auth->{'token'} = $this->auth->generateToken($auth);
		$transformer = new Item($auth, new UserTransformer);
    $data = $this->container->fractal->createData($transformer)->toArray();

		return $response->withJson([
			'status' => 200,
			'message' => 'successful'
			'data' => $data
		], 200);
	}

	private function validateLoginRequest($request)
	{
		return $this->validator->validate($request,
      [
        'email' => v::noWhitespace()->notEmpty(),
        'password' => v::noWhitespace()->notEmpty(),
      ]
    );
	}
}
