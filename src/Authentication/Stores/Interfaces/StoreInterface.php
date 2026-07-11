<?php

namespace NaN\Authentication\Stores\Interfaces;

interface StoreInterface {
	public function patch(array $data): void;

	public function pull(array $data): mixed;

	public function push(array $data): bool;

	public function purge(array $data): void;
}
