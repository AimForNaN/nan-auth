<?php

namespace NaN\Authentication\Sessions\Sql;

use NaN\Database\Sql\Schema\{
	Interfaces\SqlTableInterface,
	SqlField,
	Traits\SqlTableTrait,
};

class SqlSessionTable implements SqlTableInterface {
	use SqlTableTrait;

	public const string NAME = 'sessions';

	public function fields(): \Generator {
		yield SqlField::dateTime('expires')->nullable();
		yield SqlField::varchar('identity');
		yield SqlField::varchar('token')->primary();
	}
}
