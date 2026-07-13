<?php

namespace NaN\Authentication\Identities\Sql;

use NaN\Authentication\Identifiers;
use NaN\Authentication\Identities\Traits\IdentityTrait;
use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Database\Traits\EntityTrait;

class SqlUser implements IdentityInterface {
	use EntityTrait, IdentityTrait {
		EntityTrait::fromArray insteadof IdentityTrait;
	}

	private(set) string $id;

	public ?string $display_name;

	public Identifiers $identifiers;

	public function getIdentifiers(): Identifiers {
		return new Identifiers();
	}
}
