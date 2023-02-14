<?php

namespace Database\Factories;

use Cartelo\Models\Mobile;
use Illuminate\Database\Eloquent\Factories\Factory;

class MobileFactory extends Factory
{
	protected $model = Mobile::class;

	public function definition()
	{
		return [
			'restaurant_id' => null,
			'name' => 'Mobile ' . uniqid(),
			'prefix' => 48,
			'number' => $this->faker->numerify('#########'),
		];
	}

	// Indicate that the area is visible.
	public function hidden()
	{
		return $this->state(function (array $attributes) {
			return [
				'visible' => 0,
			];
		});
	}
}
