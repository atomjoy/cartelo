<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponTable extends Migration
{
	public function up()
	{
		Schema::create('coupons', function (Blueprint $table) {
			$table->id();
			$table->string('code');
			$table->string('description')->nullable(true);
			$table->enum('type', ['amount', 'percent'])->nullable()->default('percent');
			$table->unsignedDecimal('discount', 15, 2)->default(0.00);
			$table->unsignedTinyInteger('max_order_percent')->nullable()->default(50);
			$table->unsignedTinyInteger('active')->nullable()->default(1);
			$table->timestamp('expired_at')->nullable(true);
			$table->timestamp('used_at')->nullable(true);
			$table->timestamps();
			$table->softDeletes();

			$table->unsignedBigInteger('user_id')->nullable(true);
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
			$table->unique(['code', 'user_id']);
		});
	}

	public function down()
	{
		Schema::dropIfExists('coupons');
	}
}
