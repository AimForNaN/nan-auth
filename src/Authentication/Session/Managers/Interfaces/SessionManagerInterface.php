<?php

namespace NaN\Authentication\Session\Managers\Interfaces;

use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

interface SessionManagerInterface {
	/**
	 * Create a valid session for user.
	 *
	 * @param mixed $user
	 *
	 * @return mixed A valid session object or null on failure.
	 */
	public function createSession(mixed $user): mixed;

	/**
	 * Delete (invalidate) a session.
	 *
	 * @param mixed $session
	 *
	 * @return bool Whether it succeeded.
	 */
	public function destroySession(mixed $session): bool;

	/**
	 * Retrieve session matching client cookie data.
	 *
	 * @param PsrServerRequestInterface $request
	 *
	 * @return mixed A valid session object or null on failure.
	 */
	public function fromClient(PsrServerRequestInterface $request): mixed;

	/**
	 * Retrieve latest active session from user.
	 *
	 * @param mixed $user
	 *
	 * @return mixed A valid session object or null on failure.
	 */
	public function fromUser(mixed $user): mixed;
}
