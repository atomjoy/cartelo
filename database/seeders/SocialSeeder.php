<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Cartelo\Models\Social;
use Cartelo\Models\Restaurant;

class SocialSeeder extends Seeder
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
			$a = Social::factory()->count(3)->make();
			$item->socials()->saveMany($a);
		});
	}
}
