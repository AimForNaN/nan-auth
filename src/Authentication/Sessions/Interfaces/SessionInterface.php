<?php

namespace NaN\Authentication\Sessions\Interfaces;

interface SessionInterface extends IdentityRefInterface {
	public string $token { get; }

	public function withToken(string $token): SessionInterface;
}
