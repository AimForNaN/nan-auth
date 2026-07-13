<?php

namespace NaN\Authentication\Credentials\Interfaces;

use NaN\Authentication\CredentialType;
use NaN\Authentication\Interfaces\IdentityRefInterface;
use NaN\Database\Interfaces\EntityInterface;

interface CredentialInterface extends EntityInterface, IdentityRefInterface {
	public ?string $expires { get; }

	public string $type { get; }

	public string $value { get; }

	public function withType(CredentialType $type): CredentialInterface;

	public function withValue(string $value): CredentialInterface;
}
