<?php

namespace NaN\Authentication\Session\Managers;

use NaN\Authentication\{
	Session\Interfaces\SessionInterface,
	User\Interfaces\UserInterface,
	Session,
};
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class DatabaseSessionManager implements Interfaces\SessionManagerInterface {
	public function destroySession(SessionInterface $session): bool {
		return false;
	}

	public function fromClient(PsrServerRequestInterface $request): SessionInterface {
		return new Session();
	}

	public function fromUser(UserInterface $user): SessionInterface {
		return new Session();
	}
}
