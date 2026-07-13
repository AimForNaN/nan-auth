<?php

use Illuminate\Database\{
	Capsule\Manager as CapsuleManager,
	Migrations\DatabaseMigrationRepository,
	Migrations\Migrator,
};
use Illuminate\Filesystem\Filesystem;
use NaN\Authentication\{
	CredentialType,
	Hashers\BcryptHasher,
	IdentifierType,
};
use NaN\Authentication\Stores\Sql\{
	SqlCredentialStore,
	SqlIdentifierStore,
	SqlIdentityStore,
};
use NaN\Database\Sql\SqlConnection;

function base_path(string $path): string {
	return __DIR__ . '/' . $path;
}

pest()->beforeAll(function () {
	static $migrated = false;

	if ($migrated) {
		return;
	}

	$db_path = ':memory:';//base_path('test.sqlite');
	$pdo = \PDO::connect('sqlite:' . $db_path);
	$pdo = null;

	$capsule = new CapsuleManager();
	$capsule->addConnection([
		'driver' => 'sqlite',
		'database' => $db_path,
	]);

	$capsule->setAsGlobal();

	$connection_resolver = $capsule->getDatabaseManager();
	$pdo = $connection_resolver->connection()->getPdo();
	$migrator = new Migrator(
		new DatabaseMigrationRepository(
			$connection_resolver,
			'__migrations',
		),
		$connection_resolver,
		new Filesystem(),
	);

	if (!$migrator->repositoryExists()) {
		$migrator->getRepository()->createRepository();
	}

	$migrator->run(__DIR__ . '/../migrations');

	$sql = new SqlConnection($pdo);
	$credential_store = new SqlCredentialStore($sql);
	$identifier_store = new SqlIdentifierStore($sql);
	$identity_store = new SqlIdentityStore($sql);
	$identity = $identity_store->push();
	$credential = $credential_store->push([
		'identity' => $identity->id,
		'type' => CredentialType::Password->value,
		'value' => new BcryptHasher()->hash('password'),
	]);
	$identifier = $identifier_store->push([
		'identity' => $identity->id,
		'type' => IdentifierType::Email->value,
		'value' => 'test@example.com',
	]);

	$migrated = true;
});
