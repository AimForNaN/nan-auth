<?php

namespace NaN\Authentication;

use NaN\Authentication\Session\Managers\Interfaces\SessionManagerInterface;
use NaN\Authentication\User\Managers\Interfaces\UserManagerInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class Authenticator {
	public function __construct(
		protected UserManagerInterface $_user_manager,
		protected SessionManagerInterface $_session_manager,
	) {
	}

	public function isValidSession(mixed $session): bool {
		return $this->_session_manager->isValid($session);
	}

	public function isValidUser(mixed $user): bool {
		return $this->_user_manager->isValid($user);
	}

	public function login(PsrServerRequestInterface $request): mixed {
		$user = $this->_user_manager->fromClient($request);
		return $this->_session_manager->createSession($request, $user);
	}

	public function logout(PsrServerRequestInterface $request): bool {
		$session = $this->_session_manager->fromClient($request);
		return $this->_session_manager->destroySession($session);
	}

	public function logoutUser(mixed $user): bool {
		$session = $this->_session_manager->fromUser($user);
		return $this->_session_manager->destroySession($session);
	}

	public function register(PsrServerRequestInterface $request): mixed {
		return $this->_user_manager->register($request);
	}

	public function validate(PsrServerRequestInterface $request): bool {
		return $this->_user_manager->validate($request);
	}
}
