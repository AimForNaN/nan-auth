<?php

namespace NaN\Authentication\Sessions\Interfaces;

use NaN\Authentication\Interfaces\IdentityRefInterface;

interface SessionInterface extends IdentityRefInterface {
	public string $token { get; }

	public static function fromArray(array $data): SessionInterface;

	public function withToken(string $token): SessionInterface;
}
