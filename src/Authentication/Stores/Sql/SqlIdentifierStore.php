<?php

namespace NaN\Authentication\Stores\Sql;

use NaN\Authentication\Identifiers\{
	Identifier,
	Interfaces\IdentifierInterface,
};
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Database\Sql\Query\Statements\{
	Clauses\WhereClause,
	DeleteStatement,
	InsertStatement,
	SelectStatement,
	UpdateStatement,
};
use NaN\Database\Sql\SqlConnection;

readonly class SqlIdentifierStore implements StoreInterface {
	public function __construct(
		private SqlConnection $__connection,
	) {
	}

	public function patch(array $data): void {
		$query = $this->__connection->queryBuilder();
		/** @var UpdateStatement $update_statement */
		$update_statement = $query->patch();
		$identifier = $data['value'];

		// Prevent overriding identifier!
		unset($data['value']);

		// @todo Create identifiers table class!
		$update_statement
			->update('identifiers')
			->with($data)
			->where(function (WhereClause $where) use ($identifier) {
				$where->is('value', '=', $identifier);
			})
		;

		$this->__connection->exec($update_statement);
	}

	public function pull(array $data): ?IdentifierInterface {
		$query = $this->__connection->queryBuilder();
		/** @var SelectStatement $select_statement */
		$select_statement = $query->pull();

		// @todo Create identifiers table class!
		$select_statement
			->select()
			->from('identifiers')
			->where(function (WhereClause $where) use ($data) {
				$where->is('value', '=', $data['value']);
			})
			->limit(1)
		;

		/** @var \PDOStatement|false $statement */
		if ($statement = $this->__connection->exec($select_statement)) {
			return $statement->fetchObject(Identifier::class) ?: null;
		}

		return null;
	}

	public function push(array $data): bool {
		$query = $this->__connection->queryBuilder();
		/** @var InsertStatement $insert_statement */
		$insert_statement = $query->push();

		// @todo Create identifiers table class!
		$insert_statement
			->insert($data)
			->into('identifiers')
		;

		return (bool)$this->__connection->exec($insert_statement);
	}

	public function purge(array $data): void {
		$query = $this->__connection->queryBuilder();
		/** @var DeleteStatement $delete_statement */
		$delete_statement = $query->purge();

		// @todo Create identifiers table class!
		$delete_statement
			->from('identifiers')
			->where(function (WhereClause $where) use ($data) {
				$where
					->is('value', '=', $data['value'])
				;
			})
		;

		$this->__connection->exec($delete_statement);
	}
}
