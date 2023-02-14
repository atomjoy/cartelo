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
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('slug');
			$table->string('image')->nullable()->default('');
			$table->string('about')->nullable()->default('');
			$table->unsignedTinyInteger('on_stock')->nullable()->default(1); // Show but withhout add to cart button
			$table->unsignedInteger('sorting')->nullable()->default(0);
			$table->unsignedTinyInteger('visible')->nullable()->default(1);
			$table->timestamps();
			$table->softDeletes();

			$table->unique('name');
			$table->unique('slug');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('products');
	}
};
