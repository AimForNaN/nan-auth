<?php

namespace NaN\Authentication\Session;

class SqlSession {
	const string DATABASE_TABLE = 'sessions';

	public string $id;
	public string $expires;
	public string $token;
	public string $user_agent;
	public int $user_id;
	public string $user_ip;
}
