<?php

namespace NaN\Authentication\User;

class DatabaseUser {
	const string DATABASE_TABLE = 'users';

	public int $id;
	public string $email;
	public string $password;
	public ?string $display_name;
	public ?string $email_verified_at;
}
