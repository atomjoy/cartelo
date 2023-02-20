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
		Schema::create('category_product', function (Blueprint $table) {
			$table->id('id');

			$table->unsignedBigInteger('category_id')->default(0)->index();
			$table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');

			$table->unsignedBigInteger('product_id')->default(0)->index();
			$table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');

			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//Schema::disableForeignKeyConstraints();
		Schema::dropIfExists('category_product');
		//Schema::enableForeignKeyConstraints();
	}
};
