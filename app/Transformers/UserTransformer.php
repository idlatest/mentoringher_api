<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
	protected $userId;
	
	function __construct($userId = null)
	{
		$this->userId = $userId;
	}

	public function transform(User $user)
	{
		return [
			'id' => $user->id,
			'firstName' => $user->first_name,
			'lastName' => $user->last_name,
			'phoneNumber' => $user->phone_number,
			'email' => $user->email,
			'avatar' => $user->avatar,
			'isDoctor' => $user->is_doctor,
			'education' => $user->education,
			'boardCertification' => $user->board_certification,
			'legalName' => $user->legal_name,
			// 'bio' => $user->bio,
			'token' => $user->token,
			'createdAt' => $user->created_at->toDateTimeString(),
			'createdAtHuman' => $user->created_at->diffForHumans(),
		];
	}
}