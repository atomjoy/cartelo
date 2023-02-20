<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Assign additive groups to the variant.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addon_group_variant', function (Blueprint $table) {
			$table->id('id');

			$table->unsignedBigInteger('variant_id')->default(0)->index();
			$table->foreign('variant_id')->references('id')->on('variants')->onUpdate('cascade')->onDelete('cascade');

			$table->unsignedBigInteger('addon_group_id')->default(0)->index();
			$table->foreign('addon_group_id')->references('id')->on('addon_groups')->onUpdate('cascade')->onDelete('cascade');

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
		Schema::dropIfExists('addon_group_variant');
	}
};
