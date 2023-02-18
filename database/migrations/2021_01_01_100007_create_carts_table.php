<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
	public function up()
	{
		Schema::create('carts', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->enum('delivery_method', ['home', 'pickup', 'restaurant'])->nullable()->default('home');
			$table->unsignedTinyInteger('active')->nullable()->default(1);
			$table->string('ip');
			$table->timestamps();
			$table->softDeletes();

			$table->unsignedBigInteger('area_id')->nullable(true);
			$table->unsignedBigInteger('user_id')->nullable(true);
			$table->unsignedBigInteger('coupon_id')->nullable(true);
			$table->foreign('area_id')->references('id')->on('areas')->onUpdate('cascade')->onDelete('set null');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
			$table->foreign('coupon_id')->references('id')->on('coupons')->onUpdate('cascade')->onDelete('set null');
		});

		Schema::create('cart_variants', function (Blueprint $table) {
			$table->id();
			$table->uuid('cart_id');
			$table->unsignedBigInteger('variant_id');
			$table->unsignedInteger('quantity')->nullable()->default(1);
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('variant_id')->references('id')->on('variants')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('cart_id')->references('id')->on('carts')->onUpdate('cascade')->onDelete('cascade');
		});

		Schema::create('cart_variant_addons', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('cart_variant_id');
			$table->unsignedBigInteger('addon_id')->nullable();
			$table->unsignedInteger('quantity')->nullable()->default(1);
			$table->timestamps();
			$table->softDeletes();

			$table->unique(['cart_variant_id', 'addon_id']);
			$table->foreign('cart_variant_id')->references('id')->on('cart_variants')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('addon_id')->references('id')->on('addons')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::disableForeignKeyConstraints();

		Schema::dropIfExists('cart_variant_addons');
		Schema::dropIfExists('cart_variant');
		Schema::dropIfExists('carts');

		Schema::enableForeignKeyConstraints();
	}
}
