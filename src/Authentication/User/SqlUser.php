<?php

namespace NaN\Authentication\User;

class SqlUser {
	const string DATABASE_TABLE = 'users';

	public int $id;
	public ?string $display_name;
	public string $email;
	public ?string $email_verified_at;
	public string $password;
}
