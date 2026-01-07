<?php

namespace NaN\Authentication\Session;

use NaN\Database\Sql\{
	Interfaces\SqlTableInterface,
	Traits\SqlTableTrait,
};
use Nette\Schema\{
	Expect,
	Schema,
};

class SqlSessionTable implements SqlTableInterface {
	use SqlTableTrait;

	public const string TABLE_NAME = 'sessions';

	public static function schema(): Schema {
		return Expect::from(new SqlSession(), []);
	}
}
