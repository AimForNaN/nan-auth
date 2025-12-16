<?php

namespace NaN\Authentication\Session;

class DatabaseSession {
	const string DATABASE_TABLE = 'sessions';

	public string $payload;
	public int $user_id;
	public string $expiration;
}
