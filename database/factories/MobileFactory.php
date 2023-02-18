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
			'number' => $this->faker->numerify('#########'),
			'prefix' => 48,
			'sorting' => 0,
			'visible' => 1,
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

	public function sorting(int $number)
	{
		return $this->state(function (array $attributes) use ($number) {
			return [
				'sorting' => $number,
			];
		});
	}
}
