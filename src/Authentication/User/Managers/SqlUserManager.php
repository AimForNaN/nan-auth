<?php

namespace NaN\Authentication\User\Managers;

use NaN\Authentication\{
	Session\SqlSession,
	User\SqlUser,
};
use NaN\Database\Drivers\Interfaces\DriverInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class SqlUserManager implements Interfaces\UserManagerInterface {
	public function __construct(
		protected DriverInterface $sql_driver,
	) {
	}

	protected function _assertSession(?SqlSession $session): void {}

	protected function _assertUser(?SqlUser $session): void {}

	public function fromClient(PsrServerRequestInterface $request): ?SqlUser {
		return null;
	}

	/**
	 * @param SqlSession $session
	 *
	 * @return SqlUser|null
	 */
	public function fromSession(mixed $session): ?SqlUser {
		$this->_assertSession($session);

		return null;
	}

	/**
	 * @param SqlUser $user
	 *
	 * @return bool
	 */
	public function isValid(mixed $user): bool {
		$this->_assertUser($user);

		if ($user) {
			return (bool)\date_create($user->email_verified_at);
		}

		return false;
	}

	public function register(PsrServerRequestInterface $request): ?SqlUser {
		return null;
	}

	public function validate(PsrServerRequestInterface $request): bool {
		return false;
	}
}
