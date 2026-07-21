<?php

namespace NaN\Authentication\Stores;

class RedisStore implements Interfaces\StoreInterface {
	public function patch(array $data): bool {
		return false;
	}

	public function pull(array $data): mixed {
	}

	public function push(array $data): mixed {
	}

	public function purge(array $data): bool {
		return false;
	}
}
