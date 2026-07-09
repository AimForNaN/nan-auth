<?php

namespace NaN\Authentication;

readonly class SqlAuthenticator implements Interfaces\AuthenticatorInterface {
	public function __construct(
		private Interfaces\RegistrarInterface $__registrar,
	) {

	}
	public function authenticateChallenge(mixed $challenge): ?object {
	}

	public function authenticateSession(mixed $session): ?object {
	}

	public function authenticateUser(mixed $user): ?object {
		// @todo: $user = authenticated user!

		return $this->__registrar->registerSession([
			'user' => $user->id,
			'session' => \bin2hex(\random_bytes(32)),
			'expires' => new \DateTimeImmutable('+15 days'),
		]);
	}
}
