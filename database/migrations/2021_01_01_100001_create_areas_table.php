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
		Schema::create('areas', function (Blueprint $table) {
			$table->id();
			$table->string('name', 100);
			$table->string('about', 255);
			$table->unsignedDecimal('cost', 15, 2)->nullable()->default(0.00); // Delivery cost
			$table->unsignedDecimal('min_order_cost', 15, 2)->nullable()->default(0.00); // Delivery min order cost
			$table->unsignedDecimal('free_from', 15, 2)->nullable()->default(0.00); // Delivery free from price
			$table->unsignedTinyInteger('on_free_from')->nullable()->default(0); // Enable free from delivery
			$table->unsignedInteger('time')->nullable()->default(60); // Delivery time
			$table->polygon('polygon')->nullable(true);
			$table->unsignedInteger('sorting')->nullable()->default(0);
			$table->unsignedTinyInteger('visible')->nullable()->default(1);
			$table->timestamps();
			$table->softDeletes();

			$table->unsignedBigInteger('restaurant_id')->nullable(true);
			$table->unique(['restaurant_id', 'name']);
			$table->foreign('restaurant_id')->references('id')->on('restaurants')->onUpdate('cascade')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('areas');
	}
};
