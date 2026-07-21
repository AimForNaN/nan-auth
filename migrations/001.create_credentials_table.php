<?php

use Illuminate\Database\{
	Capsule\Manager as Capsule,
	Migrations\Migration,
	Schema\Blueprint,
};

return new class extends Migration {
	public function down(): void {
		Capsule::schema()->dropIfExists('credentials');
	}

	public function up(): void {
		Capsule::schema()->create('credentials', function (Blueprint $table) {
			$table->uuid('identity');
			$table->string('type');
			$table->string('value');
			$table->dateTime('expires')->nullable();

			$table->foreign('identity')->references('id')->on('users');
		});
	}
};
