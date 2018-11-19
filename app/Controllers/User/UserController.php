<?php

namespace App\Controllers\User;

use App\Auth\Auth;
use App\Controllers\Controller;
use App\Transformers\UserTransformer;
use League\Fractal\Resource\Item;

class UserController extends Controller
{
	public function show($request, $response)
	{
		$user = $this->auth->requestUser($request);
		
		if (!$user) {
			return $response->withStatus(404)->withJson(['user' => 'User not found']);
		}

		$user->{'token'} = $this->auth->generateToken($user);

		$transformer = new Item($user, new UserTransformer);
    		$data = $this->container->fractal->createData($transformer)->toArray();

		return $response->withJson(['user' => $data]);
	}

	public function update($request, $response)
	{
		$user = $this->auth->requestUser($request);
		
		if (!$user) {
			return $response->withStatus(404)->withJson(['user' => 'User not found']);
		}

		if ($action = $request->getParam('action')) {
      if ($action == 'avatar_update') {
      	$user->avatar = null != $request->getParam('avatar') ? $request->getParam('avatar') : $user->avatar;
      	$user->save();
      }
    } else {
			$user->first_name = null != $request->getParam('first_name') ? $request->getParam('first_name') : $user->first_name;
			$user->last_name = null != $request->getParam('last_name') ? $request->getParam('last_name') : $user->last_name;
			$user->email = null != $request->getParam('email') ? $request->getParam('email') : $user->email;
			$user->phone_number = null != $request->getParam('phone_number') ? $request->getParam('phone_number') : $user->phone_number;
			$user->bio = null != $request->getParam('bio') ? $request->getParam('bio') : $user->bio;
			$user->save();
    }

    $user->{'token'} = $this->auth->generateToken($user);

		$transformer = new Item($user, new UserTransformer);
    $data = $this->container->fractal->createData($transformer)->toArray();

    return $response->withJson(['user' => $data], 200);
	}

	public function changePassword($request, $response)
	{
		$user = $this->auth->requestUser($request);
		
		if (!$user) {
			return $response->withStatus(404)->withJson(['user' => 'User not found']);
		}

		if (!password_verify($request->getParam('oldPassword'), $user->password)) {
			return $response->withStatus(422)->withJson(['error' => 'Password does not match']);
		}

		$user->password = null != $request->getParam('newPassword') ? 
			password_hash($request->getParam('newPassword'), PASSWORD_DEFAULT) : $user->password;
		$user->save();

		$user->{'token'} = $this->auth->generateToken($user);

		$transformer = new Item($user, new UserTransformer);
    $data = $this->container->fractal->createData($transformer)->toArray();

    return $response->withJson(['user' => $data]);
	}
}
