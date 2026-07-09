<?php

namespace NaN\Authentication\Codecs;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class SymmetricJwtCodec implements Interfaces\CodecInterface {
	public function __construct(
		protected readonly string $shared_key,
	) {
	}

	public function decode(string $data): mixed {
		return (array)JWT::decode($data, new Key($this->shared_key, 'HS256'));
	}

	public function encode(mixed $data): string {
		return JWT::encode($data, $this->shared_key, 'HS256');
	}
}
