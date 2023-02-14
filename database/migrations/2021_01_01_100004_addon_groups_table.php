<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addon_groups', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->enum('size', ['S', 'M', 'L', "XL", "XXL", "XXXL"])->nullable()->default('S');
			$table->unsignedTinyInteger('multiple')->nullable()->default(1);
			$table->unsignedTinyInteger('required')->nullable()->default(0);
			$table->unsignedInteger('sorting')->nullable()->default(0);
			$table->unsignedTinyInteger('visible')->nullable()->default(1);
			$table->string('about')->nullable()->default('');
			$table->timestamps();
			$table->softDeletes();

			$table->unique(['name', 'size']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('addon_groups');
	}
};
