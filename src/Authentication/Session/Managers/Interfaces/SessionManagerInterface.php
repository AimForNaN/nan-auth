<?php

namespace NaN\Authentication\Session\Managers\Interfaces;

use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

interface SessionManagerInterface {
	/**
	 * Used to create a valid session for user.
	 *
	 * @param mixed $user
	 *
	 * @return mixed A valid session object or null on failure.
	 */
	public function createSession(mixed $user): mixed;

	/**
	 * Used to invalidate a session.
	 *
	 * @param mixed $session
	 *
	 * @return bool Whether it succeeded.
	 */
	public function destroySession(mixed $session): bool;

	/**
	 * Used to get session from client cookie data.
	 *
	 * @param PsrServerRequestInterface $request
	 *
	 * @return mixed A valid session object or null on failure.
	 */
	public function fromClient(PsrServerRequestInterface $request): mixed;

	/**
	 * Used to get an active session from a user object.
	 *
	 * @param mixed $user
	 *
	 * @return mixed A valid session object or null on failure.
	 */
	public function fromUser(mixed $user): mixed;
}
