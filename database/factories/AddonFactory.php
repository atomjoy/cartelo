<?php

namespace Database\Factories;

use Cartelo\Models\Addon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddonFactory extends Factory
{
	protected $model = Addon::class;

	public function definition()
	{
		return [
			'name' => 'Addon ' . uniqid(),
			'price' => $this->faker->randomFloat(2, 1, 3),
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
