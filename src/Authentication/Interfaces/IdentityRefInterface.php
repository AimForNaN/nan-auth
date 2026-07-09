<?php

namespace NaN\Authentication\Interfaces;

interface IdentityRefInterface {
	public ?string $identity { get; }

	public function withIdentity(string $identity): IdentityRefInterface;
}
