<?php

namespace NaN\Authentication;

use NaN\Authentication\{
	Session\Managers\Interfaces\SessionManagerInterface,
	Session\Managers\DatabaseSessionManager,
	User\Managers\DatabaseUserManager,
	User\Managers\Interfaces\UserManagerInterface,
};
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class Authenticator {
	public function __construct(
		protected UserManagerInterface $user_manager = new  DatabaseUserManager(),
		protected SessionManagerInterface $session_manager = new DatabaseSessionManager(),
	) {
	}

	public function login(PsrServerRequestInterface $request): mixed {
		$user = $this->user_manager->fromClient($request);
		return $this->session_manager->createSession($user);
	}

	public function loginUser(mixed $user): mixed {
		return $this->session_manager->createSession($user);
	}

	public function logout(PsrServerRequestInterface $request): bool {
		$session = $this->session_manager->fromClient($request);
		return $this->session_manager->destroySession($session);
	}

	public function logoutUser(mixed $user): bool {
		$session = $this->session_manager->fromUser($user);
		return $this->session_manager->destroySession($session);
	}

	public function register(PsrServerRequestInterface $request): mixed {
		return $this->user_manager->register($request);
	}
}
