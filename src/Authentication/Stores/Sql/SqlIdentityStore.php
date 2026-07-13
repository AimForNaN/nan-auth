<?php

namespace NaN\Authentication\Stores\Sql;

use NaN\Authentication\Identities\Interfaces\IdentityInterface;
use NaN\Authentication\Identities\Sql\SqlUser;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Database\Sql\Query\Statements\{
	Clauses\WhereClause,
	DeleteStatement,
	InsertStatement,
	SelectStatement,
	UpdateStatement,
};
use NaN\Database\Sql\Query\Renderers\SqlQueryRenderer;
use NaN\Database\Sql\SqlConnection;
use Ramsey\Uuid\Uuid;

readonly class SqlIdentityStore implements StoreInterface {
	public function __construct(
		private SqlConnection $__connection,
	) {
	}

	/**
	 * @param array $data Expects an array with `id` and `display_name` key.
	 *
	 * @return bool If the statement executed successful.
	 */
	public function patch(array $data): bool {
		$query = $this->__connection->queryBuilder();
		/** @var UpdateStatement $update_statement */
		$update_statement = $query->patch();

		$update_statement->update('users')
			->with([
				'display_name' => $data['display_name'],
			])
			->where(function (WhereClause $where) use ($data) {
				$where->is('id', '=', $data['id']);
			})
		;

		return (bool)$this->__connection->exec($update_statement);
	}

	/**
	 * @param array $data Expects an array with `id` key.
	 *
	 * @return IdentityInterface|null
	 */
	public function pull(array $data): ?IdentityInterface {
		$query = $this->__connection->queryBuilder();
		/** @var SelectStatement $select_statement */
		$select_statement = $query->pull();

		$select_statement
			->select(['*'])
			->from('users')
			->where(function (WhereClause $where) use ($data) {
				$where->is('id', '=', $data['id']);
			})
		;
		var_dump(new SqlQueryRenderer()->render($select_statement->toAst()));

		if ($statement = $this->__connection->exec($select_statement)) {
			return $statement->fetchObject(SqlUser::class) ?: null;
		}

		return null;
	}

	/**
	 * @param array $data An optional array with a `display_name` key.
	 */
	public function push(array $data = []): ?IdentityInterface {
		$query = $this->__connection->queryBuilder();
		/** @var InsertStatement $insert_statement */
		$insert_statement = $query->push();
		$id = Uuid::uuid7();
		$faker = \Faker\Factory::create();

		$insert_statement
			->insert([
				'id' => $id->toString(),
				'display_name' => $data['display_name'] ?? $faker->bothify('anonymous-**********'),
			])
			->into('users')
		;

		if ($this->__connection->exec($insert_statement)) {
			return $this->pull(['id' => $id->toString()]);
		}

		return null;
	}

	/**
	 * @param array $data Expects an array with `id` key.
	 *
	 * @return bool If the statement executed successfully.
	 */
	public function purge(array $data): bool {
		$query = $this->__connection->queryBuilder();
		/** @var DeleteStatement $delete_statement */
		$delete_statement = $query->purge();

		$delete_statement
			->from('users')
			->where(function (WhereClause $where) use ($data) {
				$where->is('id', '=', $data['id']);
			})
		;

		return (bool)$this->__connection->exec($delete_statement);
	}
}
