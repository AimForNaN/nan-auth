<?php

namespace NaN\Authentication;

use NaN\Authentication\{
	Session\Interfaces\SessionInterface,
	Session\Managers\Interfaces\SessionManagerInterface,
	Session\Managers\DatabaseSessionManager,
	User\Interfaces\UserInterface,
	User\Managers\Interfaces\UserManagerInterface,
};
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class Authenticator {
	protected ?SessionInterface $session = null;

	public function __construct(
		?PsrServerRequestInterface $request = null,
		protected UserManagerInterface $user_manager,
		protected SessionManagerInterface $session_manager = new DatabaseSessionManager(),
	) {
		$this->session = $this->session_manager->fromClient($request);
	}

	public function login(UserInterface $user): void {
		$this->session = $this->session_manager->fromUser($user);
	}

	public function logout(): bool {
		if ($this->session) {
			return $this->session_manager->destroySession($this->session);
		}

		return true;
	}

	public function withSession($session): Authenticator {
		$new = clone $this;
		$new->session = $session;
		return $new;
	}
}
