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
		Schema::create('restaurants', function (Blueprint $table) {
			$table->id();
			$table->string('name')->unique();
			$table->string('city')->default('');
			$table->string('address')->default('');
			$table->string('country')->nullable()->default('');
			$table->string('mobile')->nullable()->default('');
			$table->string('email')->nullable()->default('');
			$table->string('website')->nullable()->default('');
			$table->text('about')->nullable()->default('');

			$table->unsignedTinyInteger('on_pay_money')->nullable()->default(1);
			$table->unsignedTinyInteger('on_pay_card')->nullable()->default(0);
			$table->unsignedTinyInteger('on_pay_online')->nullable()->default(0);

			$table->unsignedTinyInteger('on_break')->nullable()->default(0);
			$table->time('break_to')->nullable(true);

			$table->unsignedTinyInteger('on_delivery')->nullable()->default(1);
			$table->unsignedTinyInteger('delivery_home')->nullable()->default(1);
			$table->unsignedTinyInteger('delivery_pickup')->nullable()->default(0);
			$table->unsignedTinyInteger('delivery_restaurant')->nullable()->default(0);

			$table->decimal('lng', 15, 6)->nullable()->default('0.000000'); // 11.1cm
			$table->decimal('lat', 15, 6)->nullable()->default('0.000000'); // 11.1cm

			$table->string('invoice_company')->nullable(true);
			$table->string('invoice_country')->nullable(true);
			$table->string('invoice_city')->nullable(true);
			$table->string('invoice_street')->nullable(true);
			$table->string('invoice_zip')->nullable(true);
			$table->string('invoice_nip')->nullable(true);

			$table->unsignedInteger('sorting')->nullable()->default(0);
			$table->unsignedTinyInteger('visible')->nullable()->default(1);

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
		Schema::dropIfExists('restaurants');
	}
};
