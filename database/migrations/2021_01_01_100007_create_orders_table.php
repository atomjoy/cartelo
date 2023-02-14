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
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			// Details
			$table->enum('status', ['waiting', 'accepted', 'canceled'])->nullable()->default('waiting');
			$table->enum('payment_method', ['money', 'card', 'online', 'cashback'])->nullable()->default('money');
			$table->enum('payment_gateway', ["payu"])->nullable(true);
			$table->enum('delivery_method', ['home', 'pickup', 'restaurant'])->nullable()->default('home');
			$table->time('delivery_hour')->nullable(true);
			// Cart
			$table->unsignedDecimal('cost', 15, 2)->nullable()->default(0.00);
			$table->unsignedDecimal('delivery_cost', 15, 2)->nullable()->default(0.00);
			$table->unsignedDecimal('delivery_discount', 15, 2)->nullable()->default(0.00);
			$table->unsignedDecimal('delivery_packaging', 15, 2)->nullable()->default(0.00);
			$table->unsignedDecimal('delivery_cashback', 15, 2)->nullable()->default(0.00);
			// Client
			$table->string('firstname')->nullable(true);
			$table->string('lastname')->nullable(true);
			$table->string('country')->nullable(true);
			$table->string('city')->nullable(true);
			$table->string('address')->nullable(true);
			$table->string('floor')->nullable(true);
			$table->string('phone')->nullable(true);
			$table->string('email')->nullable(true);
			$table->string('comment')->nullable(true);
			$table->string('ip')->nullable(true);
			$table->unsignedDecimal('lng', 15, 6)->nullable()->default(0.000000);
			$table->unsignedDecimal('lat', 15, 6)->nullable()->default(0.000000);
			// Invoice
			$table->unsignedTinyInteger('invoice')->nullable()->default(0);
			$table->string('invoice_company')->nullable(true);
			$table->string('invoice_country')->nullable(true);
			$table->string('invoice_city')->nullable(true);
			$table->string('invoice_street')->nullable(true);
			$table->string('invoice_zip')->nullable(true);
			$table->string('invoice_nip')->nullable(true);
			// Timestamps
			$table->timestamps();
			$table->softDeletes();
			// Keys
			$table->uuid('cart_id')->nullable(true);
			$table->unsignedBigInteger('restaurant_id')->nullable(true);
			$table->unsignedBigInteger('user_id')->nullable(true);
			$table->unsignedBigInteger('area_id')->nullable(true);
			$table->unsignedBigInteger('worker_id')->nullable(true);
			$table->unsignedBigInteger('coupon_id')->nullable(true);
			// References
			$table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
			$table->foreign('cart_id')->references('id')->on('carts')->onUpdate('cascade')->onDelete('set null');
			$table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
			$table->foreign('worker_id')->references('id')->on('users')->onDelete('set null');
			$table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('orders');
	}
};
