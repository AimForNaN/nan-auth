<?php

use Illuminate\Database\{
	Capsule\Manager as Capsule,
	Migrations\Migration,
	Schema\Blueprint,
};

return new class extends Migration {
	public function down(): void {
		Capsule::schema()->dropIfExists('identifiers');
	}

	public function up(): void {
		Capsule::schema()->create('identifiers', function (Blueprint $table) {
			$table->uuid('identity');
			$table->string('type');
			$table->string('value')->unique();
			$table->boolean('verified')->default(false);

			$table->foreign('identity')->references('id')->on('users');
		});
	}
};
