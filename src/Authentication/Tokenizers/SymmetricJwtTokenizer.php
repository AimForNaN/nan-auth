<?php

namespace NaN\Authentication\Tokenizers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class SymmetricJwtTokenizer implements Interfaces\TokenizerInterface {
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
