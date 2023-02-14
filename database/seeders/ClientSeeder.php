<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Cartelo\Models\Client;
use Cartelo\Models\Order;

class ClientSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$list = Order::factory(1)->create();

		$list->each(function ($item) {
			$a = Client::factory()->count(1)->make();
			$item->client()->save($a);
		});
	}
}
