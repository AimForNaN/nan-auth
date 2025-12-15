<?php

namespace NaN\Authentication\Session\Managers;

use NaN\Authentication\Session\DatabaseSession;
use NaN\Authentication\Tokenizer\Interfaces\TokenizerInterface;
use NaN\Authentication\User\DatabaseUser;
use NaN\Database\Drivers\Interfaces\DriverInterface;
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;

class DatabaseSessionManager implements Interfaces\SessionManagerInterface {
	public function __construct(
		protected DriverInterface $sql_driver,
		protected TokenizerInterface $tokenizer,
	) {
	}

	protected function assertSession(?DatabaseSession $session): void {}

	protected function assertUser(?DatabaseUser $session): void {}

	public function createSession(mixed $user): ?DatabaseSession {
		$this->assertUser($user);

		return null;
	}

	public function destroySession(mixed $session): bool {
		$this->assertSession($session);

		return false;
	}

	public function fromClient(PsrServerRequestInterface $request): ?DatabaseSession {
		return null;
	}

	public function fromUser(mixed $user): ?DatabaseSession {
		$this->assertUser($user);

		return null;
	}
}
