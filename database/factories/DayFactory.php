<?php

namespace Database\Factories;

use Cartelo\Models\Day;
use Illuminate\Database\Eloquent\Factories\Factory;

class DayFactory extends Factory
{
	protected $model = Day::class;

	public function definition()
	{
		return [
			'restaurant_id' => null,
			'number' => '1',
			'open' => rand(10, 12) . ':00:00',
			'close' => rand(17, 22) . ':00:00',
			'closed' => 0
		];
	}

	public function closed()
	{
		return $this->state(function (array $attributes) {
			return [
				'closed' => 1,
			];
		});
	}
}
