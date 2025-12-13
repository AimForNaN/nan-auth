<?php

namespace NaN\Authentication\User\Managers;

use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class DatabaseUserManager implements Interfaces\UserManagerInterface {
	public function fromClient(PsrServerRequestInterface $request): mixed {
		return null;
	}
	public function fromSession(mixed $session): mixed {
		return null;
	}

	public function isValid(mixed $user): bool {
		return false;
	}

	public function validate(mixed $user): bool {
		return false;
	}

	public function register(PsrServerRequestInterface $request): mixed {
		return null;
	}
}
