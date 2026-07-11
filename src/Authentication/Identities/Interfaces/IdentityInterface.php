<?php

namespace NaN\Authentication\Identities\Interfaces;

use NaN\Authentication\Identifiers;
use NaN\Authentication\Interfaces\EntityInterface;

interface IdentityInterface extends EntityInterface {
	public static function fromArray(array $data): IdentityInterface;

	public function getIdentifiers(): Identifiers;
}
