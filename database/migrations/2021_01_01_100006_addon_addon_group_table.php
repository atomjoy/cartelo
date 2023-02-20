<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Assign add-ons to a specific add-on group.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addon_addon_group', function (Blueprint $table) {
			$table->id('id');

			$table->unsignedBigInteger('addon_id')->default(0)->index();
			$table->foreign('addon_id')->references('id')->on('addons')->onUpdate('cascade')->onDelete('cascade');

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
		Schema::dropIfExists('addon_addon_group');
	}
};
