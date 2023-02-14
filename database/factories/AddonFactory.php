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
		];
	}
}
