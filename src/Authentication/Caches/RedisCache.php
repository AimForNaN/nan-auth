<?php

namespace NaN\Authentication\Caches;

use Psr\SimpleCache\CacheInterface as PsrCacheInterface;

readonly class RedisCache implements PsrCacheInterface {
	public function __construct(
		private \Predis\Client $__client,
	) {
		// @todo Maybe lazily connect!
		if (!$this->__client->isConnected()) {
			$this->__client->connect();
		}
	}

	public function get(string $key, mixed $default = null): mixed {
		return $this->__client->get($key) ?? $default;
	}

	public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool {
		return (bool)$this->__client->set($key, $value, $ttl);
	}

	public function delete(string $key): bool {
		return (bool)$this->__client->del($key);
	}

	public function clear(): bool {
		return (bool)$this->__client->flushdb();
	}

	public function getMultiple(iterable $keys, mixed $default = null): iterable {
		return $this->__client->mget($keys) ?? $default;
	}

	public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool {
		return (bool)$this->__client->mset((array)$values, $ttl);
	}

	public function deleteMultiple(iterable $keys): bool {
		return (bool)$this->__client->del($keys);
	}

	public function has(string $key): bool {
		return (bool)$this->__client->exists($key);
	}
}
