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
		Schema::create('variants', function (Blueprint $table) {
			$table->id();
			$table->string('size', 191)->nullable(true);
			$table->unsignedDecimal('price', 15, 2)->default(0.00);
			$table->unsignedDecimal('price_sale', 15, 2)->nullable()->default(0.00);
			$table->unsignedDecimal('packaging', 15, 2)->nullable()->default(0.00);
			$table->unsignedDecimal('cashback', 15, 2)->nullable()->default(0.00);
			$table->unsignedTinyInteger('on_sale')->nullable()->default(0);
			$table->unsignedInteger('sorting')->nullable()->default(0);
			$table->unsignedTinyInteger('visible')->nullable()->default(1);
			$table->string('image')->nullable()->default('');
			$table->string('about')->nullable()->default('');
			$table->timestamps();
			$table->softDeletes();

			$table->unsignedBigInteger('product_id')->index();
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
			$table->unique(['product_id', 'size']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('variants');
	}
};
