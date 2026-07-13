<?php

namespace NaN\Authentication\Identifiers;

use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Identifiers\Traits\IdentifierTrait;
use NaN\Authentication\Traits\IdentityRefTrait;
use NaN\Database\Traits\EntityTrait;

class Identifier implements IdentifierInterface {
	use EntityTrait, IdentifierTrait, IdentityRefTrait {
		EntityTrait::fromArray insteadof IdentifierTrait;
		EntityTrait::withId insteadof IdentifierTrait;
	}

	public string $id {
		get {
			return $this->value;
		}
	}

	private(set) bool $verified = false;
}
