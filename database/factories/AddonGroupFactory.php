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
			'multiple' => rand(0, 1),
			'required' => 0,
			'about' => $this->faker->sentence(),
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
