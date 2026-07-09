<?php

namespace NaN\Authentication\Hashers\Interfaces;

interface HasherInterface {
	public function hash(string $value): string;

	public function verify(string $value, string $hash): bool;
}
