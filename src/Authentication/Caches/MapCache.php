<?php

namespace NaN\Authentication\Caches;

use Psr\SimpleCache\CacheInterface;

class MapCache implements CacheInterface {
	private array $__map = [];

	public function get(string $key, mixed $default = null): mixed {
		return $this->__map[$key] ?? $default;
	}

	public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool {
		$this->__map[$key] = $value;

		return true;
	}

	public function delete(string $key): bool {
		unset($this->__map[$key]);

		return true;
	}

	public function clear(): bool {
		$this->__map = [];

		return true;
	}

	public function getMultiple(iterable $keys, mixed $default = null): iterable {
		foreach ($keys as $key) {
			yield $key => $this->__map[$key] ?? $default;
		}
	}

	public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool {
		foreach ($values as $key => $value) {
			$this->set($key, $value);
		}

		return true;
	}

	public function deleteMultiple(iterable $keys): bool {
		foreach ($keys as $key) {
			$this->delete($key);
		}

		return true;
	}

	public function has(string $key): bool {
		return isset($this->__map[$key]);
	}
}
