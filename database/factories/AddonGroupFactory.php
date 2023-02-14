<?php

namespace Database\Factories;

use Cartelo\Models\AddonGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddonGroupFactory extends Factory
{
	protected $model = AddonGroup::class;

	public function definition()
	{
		$name = ['Ser', 'Sos', 'Dodatki', 'Mięso'];
		$size = ['S', 'M', 'L', "XL", "XXL", "XXXL"];

		return [
			'name' => $this->faker->randomElement($name) . ' ' . uniqid(),
			'size' => $this->faker->randomElement($size),
			'about' => $this->faker->sentence(),
			'multiple' => rand(0, 1),
			'required' => 0,
			'sorting' => 0
		];
	}
}
