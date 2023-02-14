<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Cartelo\Models\Day;
use Cartelo\Models\Restaurant;

class DaySeeder extends Seeder
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
			$a = Day::factory()->count(7)->sequence(fn ($sequence) => [
				'number' => $sequence->index,
				'closed' => $sequence->index > 5 ? 1 : 0,
			])->make();
			$item->days()->saveMany($a);
		});
	}
}
