<?php

namespace NaN\Authentication\Stores\Sql;

use NaN\Authentication\CredentialsCollection;
use NaN\Authentication\Credentials\Credential;
use NaN\Authentication\Stores\Interfaces\StoreInterface;
use NaN\Database\Sql\Query\Statements\Clauses\WhereClause;
use NaN\Database\Sql\Query\Statements\InsertStatement;
use NaN\Database\Sql\Query\Statements\SelectStatement;
use NaN\Database\Sql\SqlConnection;

readonly class SqlCredentialStore implements StoreInterface {
	public function __construct(
		private SqlConnection $__connection,
	) {
	}

	/**
	 * For now, only accepts passwords!
	 *
	 * @param array $data Expects an array with keys 'identity', 'type', and 'value'.
	 *
	 * @return bool If the statement executed successfully.
	 */
	public function patch(array $data): bool {
		return false;
	}

	public function pull(array $data): CredentialsCollection {
		$query = $this->__connection->queryBuilder();
		/** @var SelectStatement $select_statement */
		$select_statement = $query->pull();

		$select_statement
			->select(['*'])
			->from('credentials')
			->where(function (WhereClause $where) use ($data) {
				$where
					->is('identity', '=', $data['identity'])
					->and('type', '=', $data['type'])
				;
			})
		;

		/** @var \PDOStatement|false $statement */
		if ($statement = $this->__connection->exec($select_statement)) {
			$credentials = [];

			while ($credential = $statement->fetchObject(Credential::class)) {
				$credentials[] = $credential;
			}

			return new CredentialsCollection(...$credentials);
		}

		return new CredentialsCollection();
	}

	/**
	 * @param array $data Expects an array with keys 'identity', 'type', and 'value'.
	 */
	public function push(array $data): CredentialsCollection {
		$query = $this->__connection->queryBuilder();
		/** @var InsertStatement $insert_statement */
		$insert_statement = $query->push();

		$insert_statement
			->insert($data)
			->into('credentials')
		;

		if ($this->__connection->exec($insert_statement)) {
			return $this->pull($data);
		}

		return new CredentialsCollection();
	}

	public function purge(array $data): bool {
		return false;
	}
}
