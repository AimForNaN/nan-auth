<?php

namespace NaN\Authentication\Credentials\Interfaces;

use NaN\Authentication\CredentialType;
use NaN\Authentication\Interfaces\{
	EntityInterface,
	IdentityRefInterface,
};

interface CredentialInterface extends EntityInterface, IdentityRefInterface {
	public CredentialType $type { get; }

	public string $value { get; }

	public function withType(CredentialType $type): CredentialInterface;

	public function withValue(string $value): CredentialInterface;
}
