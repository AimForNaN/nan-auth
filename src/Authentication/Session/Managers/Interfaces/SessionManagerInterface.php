<?php

namespace NaN\Authentication\Session\Managers\Interfaces;

use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

interface SessionManagerInterface {
	public function destroySession(mixed $session): bool;
	public function fromClient(PsrServerRequestInterface $request): mixed;
	public function fromUser(mixed $user): mixed;
}
