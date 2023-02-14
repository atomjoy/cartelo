<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Cartelo\Models\Mobile;
use Cartelo\Models\Restaurant;

class MobileSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$list = Restaurant::factory(3)->create();

		$list->each(function ($item) {
			$a = Mobile::factory()->count(2)->make();
			$item->mobiles()->saveMany($a);
		});
	}
}
