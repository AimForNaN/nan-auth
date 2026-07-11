<?php

namespace NaN\Authentication\Stores\Sql;

use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use Ramsey\Uuid\Uuid;

class SqlIdentityStore implements StoreInterface {
	public function patch(array $data): void {
	}

	public function pull(array $data): ?IdentityInterface {
		return null;
	}

	public function push(array $data): bool {
		return false;
	}

	public function purge(array $data): void {
	}
}
