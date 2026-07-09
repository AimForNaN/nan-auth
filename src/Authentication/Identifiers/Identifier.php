<?php

namespace NaN\Authentication\Identifiers;

use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\IdentifierType;
use NaN\Authentication\Interfaces\IdentityRefInterface;

class Identifier implements Interfaces\IdentifierInterface {
	private(set) ?string $identity = null;

	private(set) IdentifierType $type;

	private(set) mixed $value;

	private(set) bool $verified = false;

	public function withIdentity(string $identity): IdentityRefInterface {
		$clone = clone $this;

		$clone->identity = $identity;

		return $clone;
	}

	public function withType(IdentifierType $type): IdentifierInterface {
		$clone = clone $this;

		$clone->type = $type;

		return $clone;
	}

	public function withValue(string $value): IdentifierInterface {
		$clone = clone $this;

		$clone->value = $value;

		return $clone;
	}
}
