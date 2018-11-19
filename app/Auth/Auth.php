<?php

namespace App\Auth;

use App\Models\User;
use DateTime;
use Firebase\JWT\JWT;

class Auth {

	protected $appConfig;

	const IDENTIFIER = 'email';

	public function __construct($appConfig) {
		$this->appConfig = $appConfig;
	}

	public function generateToken(User $user) {
		$start = new DateTime();
		$expires = new DateTime("now +1 year");

		$payload = [
			"iat" => $start->getTimeStamp(),
			"exp" => $expires->getTimeStamp(),
			"jti" => base64_encode(random_bytes(16)),
			'iss' => $this->appConfig['app']['url'], // Issuer
			"sub" => $user->{self::IDENTIFIER},
		];

		$secret = $this->appConfig['jwt']['secret'];
		$token = JWT::encode($payload, $secret, "HS256");

		return $token;
	}

	public function attempt($email, $password) {
		$user = User::where(self::IDENTIFIER, $email)->first();

		if (!$user) {
			return false;
		}

		if (password_verify($password, $user->password)) {
			return $user;
		}

		return false;
	}

	public function requestUser($request)
	{
		if ($token = $request->getAttribute('token')) {
			return User::where(self::IDENTIFIER, $token['sub'])->first();
		}
	}
}
