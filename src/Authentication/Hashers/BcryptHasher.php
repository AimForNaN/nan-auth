<?php

namespace NaN\Authentication\Hashers;

use NaN\Authentication\Hashers\Interfaces\HasherInterface;

class BcryptHasher implements HasherInterface {
	public function hash(string $value): string {
		return password_hash($value, PASSWORD_BCRYPT);
	}

	public function verify(string $value, string $hash): bool {
		return password_verify($value, $hash);
	}
}
