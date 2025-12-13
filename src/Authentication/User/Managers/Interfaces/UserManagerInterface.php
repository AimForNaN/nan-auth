<?php

namespace NaN\Authentication\User\Managers\Interfaces;

use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

interface UserManagerInterface {
	/**
	 * Used to pull user from client request (e.g. POST from login form).
	 *
	 * @param PsrServerRequestInterface $request
	 *
	 * @return mixed
	 */
	public function fromClient(PsrServerRequestInterface $request): mixed;

	/**
	 * Used to pull user from existing session.
	 *
	 * @param mixed $session
	 *
	 * @return mixed
	 */
	public function fromSession(mixed $session): mixed;
}
