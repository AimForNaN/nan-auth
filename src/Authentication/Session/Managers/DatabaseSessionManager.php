<?php

namespace NaN\Authentication\Session\Managers;

use NaN\Authentication\{
	Session,
};
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class DatabaseSessionManager implements Interfaces\SessionManagerInterface {
	public function createSession(mixed $user): mixed {
		return null;
	}

	public function destroySession(mixed $session): bool {
		if ($session) {}

		return false;
	}

	public function fromClient(PsrServerRequestInterface $request): mixed {
		return null;
	}

	public function fromUser(mixed $user): mixed {
		return null;
	}
}
