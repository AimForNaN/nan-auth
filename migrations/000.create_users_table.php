<?php

use Illuminate\Database\{
	Capsule\Manager as Capsule,
	Migrations\Migration,
	Schema\Blueprint,
};

return new class extends Migration {
	public function down(): void {
		Capsule::schema()->dropIfExists('users');
	}

	public function up(): void {
		Capsule::schema()->create('users', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('display_name')->unique();
		});
	}
};
