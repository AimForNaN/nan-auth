<?php

namespace NaN\Authentication;

use NaN\Authentication\{
	Session\Managers\Interfaces\SessionManagerInterface,
	User\Managers\Interfaces\UserManagerInterface,
};
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class Authenticator {
	public function __construct(
		protected UserManagerInterface $user_manager,
		protected SessionManagerInterface $session_manager,
	) {
	}

	public function isValidSession(mixed $session): bool {
		return $this->session_manager->isValid($session);
	}

	public function isValidUser(mixed $user): bool {
		return $this->user_manager->isValid($user);
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

	public function validate(PsrServerRequestInterface $request): bool {
		return $this->user_manager->validate($request);
	}
}
