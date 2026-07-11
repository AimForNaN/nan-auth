<?php

namespace NaN\Authentication\Stores\Sql;

use NaN\Authentication\Sessions\{
	Interfaces\SessionInterface,
	Sql\SqlSession,
	Sql\SqlSessionTable,
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

readonly class SqlSessionStore implements StoreInterface {
	public function __construct(
		private SqlConnection $__connection,
	) {
	}

	/**
	 * @param array $data Expects an array with `expires`, `identity` and `token` keys.
	 */
	public function patch(array $data): void {
		$query = $this->__connection->queryBuilder();
		/** @var UpdateStatement $update_statement */
		$update_statement = $query->patch();

		$update_statement
			->update(SqlSessionTable::NAME)
			->with([
				'expires' => $data['expires'],
			])
			->where(function (WhereClause $where) use ($data) {
				$where
					->is('identity', '=', $data['identity'])
					->and('token', '=', $data['token'])
				;
			})
		;

		$this->__connection->exec($update_statement);
	}

	/**
	 * @param array $data Expects an array with `token` keys.
	 *
	 * @return SessionInterface|null
	 */
	public function pull(array $data): ?SessionInterface {
		$query = $this->__connection->queryBuilder();
		/** @var SelectStatement $select_statement */
		$select_statement = $query->pull();

		$select_statement
			->select()
			->where(function (WhereClause $where) use ($data) {
				$where
					->is('token', '=', $data['token'])
					->and('expires', '>', date(\DateTimeInterface::ATOM))
				;
			})
			->from(SqlSessionTable::NAME)
			->limit(1)
		;

		/** @var \PDOStatement|false $statement */
		if ($statement = $this->__connection->exec($select_statement)) {
			return $statement->fetchObject(SqlSession::class) ?: null;
		}

		return null;
	}

	/**
	 * @param array $data Expects an array with `expires`, `identity` and `token` keys.
	 *
	 * @return bool If the statement executed successfully.
	 */
	public function push(array $data): bool {
		$query = $this->__connection->queryBuilder();
		/** @var InsertStatement $insert_statement */
		$insert_statement = $query->push();

		$insert_statement
			->insert([
				'expires' => $data['expires'],
				'identity' => $data['identity'],
				'token' => $data['token'],
			])
			->into(SqlSessionTable::NAME)
		;

		return (bool)$this->__connection->exec($insert_statement);
	}

	/**
	 * @param array $data Expects an array with `identity` and `token` keys.
	 */
	public function purge(array $data): void {
		$query = $this->__connection->queryBuilder();
		/** @var DeleteStatement $delete_statement */
		$delete_statement = $query->purge();

		$delete_statement
			->from(SqlSessionTable::NAME)
			->where(function (WhereClause $where) use ($data) {
				$where
					->is('identity', '=', $data['identity'])
					->and('token', '=', $data['token'])
				;
			})
		;

		$this->__connection->exec($delete_statement);
	}
}
