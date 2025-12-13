<?php

namespace NaN\Authentication\Session\Managers\Interfaces;

use NaN\Authentication\{
	Session\Interfaces\SessionInterface,
	User\Interfaces\UserInterface,
};
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

interface SessionManagerInterface {
	public function destroySession(SessionInterface $session): bool;
	public function fromClient(PsrServerRequestInterface $request): ?SessionInterface;
	public function fromUser(UserInterface $user): ?SessionInterface;
}
