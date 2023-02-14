<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardTable extends Migration
{
	public function up()
	{
		Schema::create('rewards', function (Blueprint $table) {
			$table->id();

			$table->enum('type', ['plus', 'minus'])->nullable()->default('plus');
			$table->unsignedDecimal('points', 15, 2);
			$table->string('description')->nullable(true);
			$table->timestamp('expired_at')->nullable(true);
			$table->timestamps();
			$table->softDeletes();

			$table->unsignedBigInteger('order_id')->nullable(true);
			$table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');

			$table->unsignedBigInteger('user_id')->nullable(true);
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	public function down()
	{
		Schema::dropIfExists('rewards');
	}
}
