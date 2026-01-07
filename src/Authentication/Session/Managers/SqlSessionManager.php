<?php

namespace NaN\Authentication\Session\Managers;

use Carbon\Carbon;
use NaN\Database\Sql\Query\Builders\SqlQueryBuilder;
use NaN\Authentication\{
	Session\SqlSession,
	Session\SqlSessionTable,
	Tokenizers\Interfaces\TokenizerInterface,
	User\SqlUser,
};
use NaN\Database\Interfaces\ConnectionInterface;
use NaN\Database\Sql\Query\Statements\{
	Clauses\WhereClause,
	DeleteStatement,
	InsertStatement,
	SelectStatement,
};
use Psr\Http\Message\ServerRequestInterface as PsrServerRequestInterface;
use Random\RandomException;

class SqlSessionManager implements Interfaces\SessionManagerInterface {
	public function __construct(
		protected ConnectionInterface $_db,
		protected TokenizerInterface $_tokenizer,
	) {
	}

	protected function _assertSession(?SqlSession $session, $allow_null = true): void {
		if (!$allow_null && !$session) {
			throw new \InvalidArgumentException('Session not set!');
		}
	}

	protected function _assertUser(?SqlUser $user, $allow_null = true): void {
		if (!$allow_null && !$user) {
			throw new \InvalidArgumentException('Session not set!');
		}
	}

	/**
	 * @throws \DateMalformedStringException
	 * @throws RandomException
	 */
	public function createSession(PsrServerRequestInterface $request, mixed $user): ?SqlSession {
		$this->_assertUser($user, false);

		$query = new SqlQueryBuilder();
		$expiration = static::expiration();
		$token = \bin2hex(\random_bytes(32));

		$stmt = $query->push(function (InsertStatement $query) use ($expiration, $request, $token, $user) {
			[$user_agent] = $request->getHeader('User-Agent');
			[$user_ip] = $request->getHeader('X-Forwarded-For');

			$query
				->insert([
					'expires' => $expiration->format(\DateTimeInterface::ATOM),
					'token' => $token,
					'user_agent' => $user_agent,
					'user_id' => $user->id,
					'user_ip' => $user_ip,
				])
				->into(SqlSessionTable::TABLE_NAME);
		})->exec($this->_db);

		if ($stmt) {
			\setcookie('user-session', $this->_tokenizer->encode($token), $expiration->getTimestamp(), secure: true);

			$stmt = $query->pull(function (SelectStatement $query) {
				$query
					->select()
					->from(SqlSessionTable::TABLE_NAME)
					->where('id', '=', $this->_db->getLastInsertId());
			})->exec($this->_db);

			/** @var \PDOStatement $stmt */
			if ($stmt) {
				return $stmt->fetchObject(SqlSession::class);
			}
		}

		return null;
	}

	/**
	 * @throws \Exception
	 */
	public function destroySession(mixed $session): bool {
		$this->_assertSession($session);

		\setcookie('user-session', '', secure: true);

		if ($session) {
			$query = new SqlQueryBuilder();

			return (bool)$query->purge(function (DeleteStatement $query) use ($session) {
				$query
					->from(SqlSession::DATABASE_TABLE)
					->where('id', $session->id);
			})->exec($this->_db);
		}

		return true;
	}

	/**
	 * @throws \DateMalformedStringException
	 */
	public static function expiration($datetime = '+15 days'): \DateTimeImmutable {
		return new \DateTimeImmutable($datetime);
	}

	public function fromClient(PsrServerRequestInterface $request): ?SqlSession {
		['user-session' => $user_session] = $request->getCookieParams();
		return null;
	}

	public function fromUser(mixed $user): ?SqlSession {
		$this->_assertUser($user);

		$query = new SqlQueryBuilder();;

		/** @var SqlUser $user */
		$stmt = $query->pull(function (SelectStatement $query) use ($user) {
			$query
				->select()
				->from(SqlSessionTable::TABLE_NAME)
				->where(function (WhereClause $where) use ($user) {
					$where->is('user_id', '=', $user->id)
						->and('expires', '>', 'NOW()');
				})
				->last('id');
		})->exec($this->_db);

		if ($stmt) {
			return $stmt->fetchObject(SqlSession::class);
		}

		return null;
	}

	public function isValid(mixed $session): bool {
		$this->_assertSession($session);

		return Carbon::now()->isBefore($session->expires);
	}
}
