<?php

namespace NaN\Authentication\Stores\Interfaces;

interface StoreInterface {
	public function patch(array $data): bool;

	public function pull(array $data): mixed;

	public function push(array $data): mixed;

	public function purge(array $data): bool;
}
