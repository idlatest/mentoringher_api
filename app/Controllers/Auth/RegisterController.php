<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;
use App\Transformers\UserTransformer;
use League\Fractal\Resource\Item;
use Respect\validation\validator as V;

class RegisterController extends Controller {

	public function register($request, $response) {

		$validation = $this->validateRegisterRequest($request);
    
    if ($validation->failed()) {
      return $response->withJson([
        'status' => false,
        'error' => $validation->getError(),
      ])->withStatus(422);
    }

		$user = User::create([
			'first_name' => $request->getParam('firstName'),
			'last_name' => $request->getParam('lastName'),
			'email' => $request->getParam('email'),
      'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
      'is_doctor' => $request->getParam('doctor') ?: 0,
      'education' => $request->getParam('education') ?: $request->getParam('education'),
      'board_certification' => $request->getParam('board_certification') ?: $request->getParam('board_certification'),
      'legal_name' => $request->getParam('legal_name') ?: $request->getParam('legal_name'),
		]);

    //$user->{'token'} = $this->auth->generateToken($user);

    $transformer = new Item($user, new UserTransformer);
    $data = $this->container->fractal->createData($transformer)->toArray();

		return $response->withJson(['user' => $data])->withStatus(200);
	}

	private function validateRegisterRequest($request)
  {
    return $this->validator->validate($request,
      [
      	'firstName' => V::notEmpty()->alpha(),
        'lastName' => v::noWhitespace()->notEmpty(),
        'email'    => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
        'password' => v::noWhitespace()->notEmpty(),
      ]
    );
  }
}
