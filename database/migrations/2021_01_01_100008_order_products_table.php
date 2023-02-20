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
		Schema::create('order_products', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('size')->nullable(true);
			$table->unsignedInteger('qty')->nullable()->default(1);
			$table->unsignedDecimal('price', 15, 2)->nullable()->default(0.00);
			$table->unsignedDecimal('price_sale', 15, 2)->nullable()->default(0.00);
			$table->unsignedDecimal('packaging', 15, 2)->nullable()->default(0.00);
			$table->unsignedDecimal('cashback', 15, 2)->nullable()->default(0.00);
			$table->unsignedInteger('on_sale')->nullable()->default(0);
			$table->timestamps();
			$table->softDeletes();

			$table->unsignedBigInteger('order_id');
			$table->unsignedBigInteger('variant_id')->nullable(true);
			$table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('variant_id')->references('id')->on('variants')->onUpdate('cascade')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('order_variants');
	}
};
