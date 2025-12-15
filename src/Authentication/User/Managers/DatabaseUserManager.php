<?php

namespace NaN\Authentication\User\Managers;

use NaN\Authentication\{
	Session\DatabaseSession,
	User\DatabaseUser,
};
use NaN\Database\Drivers\Interfaces\DriverInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class DatabaseUserManager implements Interfaces\UserManagerInterface {
	public function __construct(
		protected DriverInterface $sql_driver,
	) {
	}

	protected function assertSession(?DatabaseSession $session): void {}

	protected function assertUser(?DatabaseUser $session): void {}

	public function fromClient(PsrServerRequestInterface $request): ?DatabaseUser {
		return null;
	}

	/**
	 * @param DatabaseSession $session
	 *
	 * @return DatabaseUser|null
	 */
	public function fromSession(mixed $session): ?DatabaseUser {
		$this->assertSession($session);

		return null;
	}

	/**
	 * @param DatabaseUser $user
	 *
	 * @return bool
	 */
	public function isValid(mixed $user): bool {
		$this->assertUser($user);

		if ($user) {
			return (bool)\date_create($user->email_verified_at);
		}

		return false;
	}

	public function register(PsrServerRequestInterface $request): ?DatabaseUser {
		return null;
	}

	public function validate(PsrServerRequestInterface $request): bool {
		return false;
	}
}
