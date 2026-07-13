<?php

namespace NaN\Authentication\Identifiers\Interfaces;

use NaN\Authentication\IdentifierType;
use NaN\Authentication\Interfaces\IdentityRefInterface;
use NaN\Database\Interfaces\EntityInterface;

interface IdentifierInterface extends EntityInterface, IdentityRefInterface {
	public string $type { get; }

	public mixed $value { get; }

	// @todo Maybe not require verification!
	public bool $verified { get; }

	public function withType(IdentifierType $type): IdentifierInterface;

	public function withValue(string $value): IdentifierInterface;
}
