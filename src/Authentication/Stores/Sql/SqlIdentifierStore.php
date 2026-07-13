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

	/**
	 * For now, I see no reason to allow patching identifiers.
	 */
	public function patch(array $data): bool {
		return false;
	}

	/**
	 * @param array $data Expects an array with `value` key as identifier.
	 */
	public function pull(array $data): ?IdentifierInterface {
		$query = $this->__connection->queryBuilder();
		/** @var SelectStatement $select_statement */
		$select_statement = $query->pull();

		// @todo Create identifiers table class!
		$select_statement
			->select(['*'])
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

	/**
	 * @param array $data Expects an array with `type` as identifier type and `value` key as identifier.
	 */
	public function push(array $data): ?IdentifierInterface {
		$query = $this->__connection->queryBuilder();
		/** @var InsertStatement $insert_statement */
		$insert_statement = $query->push();

		// @todo Create identifiers table class!
		$insert_statement
			->insert($data)
			->into('identifiers')
		;

		if ($this->__connection->exec($insert_statement)) {
			return $this->pull([
				'value' => $data['value'],
			]);
		}

		return null;
	}

	/**
	 * @param array $data Expects an array with `value` key as identifier.
	 */
	public function purge(array $data): bool {
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

		return (bool)$this->__connection->exec($delete_statement);
	}
}
