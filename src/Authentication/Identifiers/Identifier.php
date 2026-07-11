<?php

namespace NaN\Authentication\Identifiers;

use NaN\Authentication\Identifiers\Interfaces\IdentifierInterface;
use NaN\Authentication\Traits\IdentityRefTrait;

class Identifier implements IdentifierInterface {
	use Traits\IdentifierTrait;
	use IdentityRefTrait;

	private(set) bool $verified = false;
}
