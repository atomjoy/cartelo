<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Cartelo\Models\Area;
use Cartelo\Models\Restaurant;

class AreaSeeder extends Seeder
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
			$a = Area::factory()->count(rand(1, 3))->make();
			$item->areas()->saveMany($a);
		});
	}
}
