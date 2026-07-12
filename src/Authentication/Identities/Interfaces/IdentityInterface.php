<?php

namespace NaN\Authentication\Identities\Interfaces;

use NaN\Authentication\Identifiers;
use NaN\Database\Interfaces\EntityInterface;

interface IdentityInterface extends EntityInterface {
	public function getIdentifiers(): Identifiers;
}
