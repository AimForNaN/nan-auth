<?php

namespace NaN\Authentication\Sessions\Sql;

use NaN\Authentication\Sessions\{
	Interfaces\SessionInterface,
	Traits\SessionTrait,
};
use NaN\Authentication\Traits\IdentityRefTrait;

class SqlSession implements SessionInterface {
	use IdentityRefTrait;
	use SessionTrait;

	public string $expires;
}
