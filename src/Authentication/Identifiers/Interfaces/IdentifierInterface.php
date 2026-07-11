<?php

namespace NaN\Authentication\Identifiers\Interfaces;

use NaN\Authentication\IdentifierType;
use NaN\Authentication\Interfaces\EntityInterface;
use NaN\Authentication\Interfaces\IdentityRefInterface;

interface IdentifierInterface extends EntityInterface, IdentityRefInterface {
	public IdentifierType $type { get; }

	public mixed $value { get; }

	// @todo Maybe not require verification!
	public bool $verified { get; }

	public static function fromArray(array $data): IdentifierInterface;

	public function withType(IdentifierType $type): IdentifierInterface;

	public function withValue(string $value): IdentifierInterface;
}
