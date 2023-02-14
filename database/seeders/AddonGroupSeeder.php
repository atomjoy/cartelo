<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Cartelo\Models\AddonGroup;

class AddonGroupSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$name = ['Sos', 'Mięso', 'Dodatki'];

		foreach ($name as $k => $v) {
			$multiple = 0;

			if ($k > 1) {
				$multiple = 1;
			}

			AddonGroup::factory()->create([
				"name" => $v,
				"size" => "S",
				'multiple' => $multiple,
			]);

			AddonGroup::factory()->create([
				"name" => $v,
				"size" => "M",
				'multiple' => $multiple,
			]);

			AddonGroup::factory()->create([
				"name" => $v,
				"size" => "L",
				'multiple' => $multiple,
			]);
		}
	}
}
