<?php

use Illuminate\Database\{
	Capsule\Manager as CapsuleManager,
	Migrations\DatabaseMigrationRepository,
	Migrations\Migrator,
};
use Illuminate\Filesystem\Filesystem;

pest()->beforeAll(function () {
	static $migrated = false;

	if ($migrated) {
		return;
	}

	$capsule = new CapsuleManager();
	$capsule->addConnection([
		'driver' => 'sqlite',
		'database' => ':memory:',
	]);

	$capsule->setAsGlobal();

	$connection_resolver = $capsule->getDatabaseManager();
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

	$migrator->run(__DIR__ . '/../Migrations');

	$migrated = true;
});
