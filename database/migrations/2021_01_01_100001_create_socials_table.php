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
		Schema::create('socials', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('link');
			$table->string('icon')->nullable()->default('https://img.icons8.com/ios-glyphs/2x/link.png');
			$table->unsignedInteger('sorting')->nullable()->default(0);
			$table->unsignedTinyInteger('visible')->nullable()->default(1);
			$table->timestamps();
			$table->softDeletes();

			$table->unsignedBigInteger('restaurant_id');
			$table->unique(['restaurant_id', 'name']);
			$table->foreign('restaurant_id')->references('id')->on('restaurants')->onUpdate('cascade')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('socials');
	}
};
