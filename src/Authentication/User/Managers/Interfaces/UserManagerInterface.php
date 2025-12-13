<?php

namespace NaN\Authentication\User\Managers\Interfaces;

use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

interface UserManagerInterface {
	/**
	 * Used to pull user from client request (e.g. POST from login form).
	 *
	 * @param PsrServerRequestInterface $request
	 *
	 * @return mixed A user object or null on failure.
	 */
	public function fromClient(PsrServerRequestInterface $request): mixed;

	/**
	 * Used to pull user from existing session.
	 *
	 * @param mixed $session
	 *
	 * @return mixed A user object or null on failure.
	 */
	public function fromSession(mixed $session): mixed;

	/**
	 * Used to check if user has been validated.
	 *
	 * @param mixed $user
	 *
	 * @return bool
	 */
	public function isValid(mixed $user): bool;

	/**
	 * Used to register a user from a client request (e.g. POST from register form).
	 *
	 * @param PsrServerRequestInterface $request
	 *
	 * @return mixed A user object or null on failure.
	 */
	public function register(PsrServerRequestInterface $request): mixed;

	/**
	 * Used to validate the user.
	 *
	 * @param mixed $user
	 *
	 * @return bool Whether it succeeded.
	 */
	public function validate(mixed $user): bool;
}
