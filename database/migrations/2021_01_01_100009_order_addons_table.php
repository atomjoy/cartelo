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
		Schema::create('order_addons', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->unsignedInteger('qty')->nullable()->default(1);
			$table->unsignedDecimal('price', 15, 2)->nullable()->default(0.00);
			$table->timestamps();
			$table->softDeletes();

			$table->unsignedBigInteger('order_product_id');
			$table->unsignedBigInteger('addon_id')->nullable(true);
			$table->foreign('order_product_id')->references('id')->on('order_products')->onDelete('cascade');
			$table->foreign('addon_id')->references('id')->on('addons')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('order_addons');
	}
};
