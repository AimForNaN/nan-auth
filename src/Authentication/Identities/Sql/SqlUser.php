<?php

namespace NaN\Authentication\Identities\Sql;

use NaN\Authentication\Identifiers;
use NaN\Authentication\Identities\Traits\IdentityTrait;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Traits\EntityTrait;

class SqlUser implements IdentityInterface {
	use EntityTrait;
	use IdentityTrait;

	public ?string $display_name;

	public Identifiers $identifiers;

	public function getIdentifiers(): Identifiers {
		return new Identifiers();
	}
}
