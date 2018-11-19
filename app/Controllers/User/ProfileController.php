<?php

namespace App\Controllers\User;

use App\Models\User;
use App\Controllers\Controller;
use App\Transformers\UserTransformer;
use League\Fractal\{
  Resource\Item,
  Resource\Collection,
  Pagination\IlluminatePaginatorAdapter
};

class ProfileController extends Controller
{

	public function index($request, $response) {
		$builder = User::query()->latest();
		$requestUser = $this->auth->requestUser($request);

		$users = $builder->paginate(100);
		$transformer = (new Collection($users->getCollection(), new UserTransformer))
			->setPaginator(new IlluminatePaginatorAdapter($users));
    $data = $this->container->fractal->createData($transformer)->toArray();

		return $response->withJson($data);
	}
	
	public function show($request, $response, array $args)
	{
		$user = User::where('id', $args['user_id'])->firstOrFail();
		$requestUser = $this->auth->requestUser($request);
		// $followingStatus = false;

		// if ($requestUser) {
		// 	$followingStatus = $requestUser->isFollowing($user->id);
		// }

		$transformer = new Item($user, new UserTransformer);
    $data = $this->container->fractal->createData($transformer)->toArray();
		
		return $response->withJson([
			'profile' => $data
		]);
	}

	public function follow($request, $response, array $args)
	{
		$requestUser = $this->auth->requestUser($request);

		if (!$requestUser) {
			return $response->withJson(['message' => 'An error occured'])->withStatus(422);
		}

		$user = User::where('id', $args['user_id'])->firstOrFail();

		$requestUser->follow($user->id);

		return $response->withJson([
			'profile' => [
				'firstName' => $user->first_name,
				'lastName' => $user->last_name,
				'email' => $user->email,
				'avatar' => $user->avatar,
				'following' => $user->isFollowedBy($requestUser->id),
			],
		]);
	}

	public function unfollow($request, $response, array $args)
	{
		$requestUser = $this->auth->requestUser($request);

		if (!$requestUser) {
			return $response->withJson(['message' => 'An error occured'])->withStatus(422);
		}

		$user = User::where('id', $args['user_id'])->firstOrFail();

		$requestUser->unfollow($user->id);

		return $response->withJson([
			'profile' => [
				'firstName' => $user->first_name,
				'lastName' => $user->last_name,
				'email' => $user->email,
				'avatar' => $user->avatar,
				'following' => $requestUser->isFollowing($user->id),
			],
		]);
	}
}