<?php

namespace NaN\Authentication\User\Managers\Interfaces;

use NaN\Authentication\{
	Session\Interfaces\SessionInterface,
	User\Interfaces\UserInterface,
};
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

interface UserManagerInterface {
	/**
	 * Used to pull user from client request (e.g. POST from login form).
	 *
	 * @param PsrServerRequestInterface $request
	 *
	 * @return UserInterface|null
	 */
	public function fromClient(PsrServerRequestInterface $request): ?UserInterface;

	/**
	 * Used to pull user from existing session.
	 *
	 * @param SessionInterface $session
	 *
	 * @return UserInterface|null
	 */
	public function fromSession(SessionInterface $session): ?UserInterface;
}
