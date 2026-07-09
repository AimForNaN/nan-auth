<?php

namespace NaN\Authentication\Credentials;

use NaN\Authentication\{
	Credentials\Interfaces\CredentialInterface,
	CredentialType,
	Interfaces\IdentityRefInterface};

class Credential implements Interfaces\CredentialInterface {
	private(set) ?string $id = null;

	private(set) ?string $identity = null;

	private(set) CredentialType $type;

	private(set) string $value;

	public function withIdentity(string $identity): IdentityRefInterface {
		$clone = clone $this;

		$clone->identity = $identity;

		return $clone;
	}

	public function withType(CredentialType $type): CredentialInterface {
		$clone = clone $this;

		$clone->type = $type;

		return $clone;
	}

	public function withValue(string $value): CredentialInterface {
		$clone = clone $this;

		$clone->value = $value;

		return $clone;
	}
}
