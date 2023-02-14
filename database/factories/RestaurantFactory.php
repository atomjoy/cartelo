<?php

namespace Database\Factories;

use Cartelo\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
	protected $model = Restaurant::class;

	public function definition()
	{
		return [
			'name' => 'Gold Duck ' . uniqid(),
			'city' => $this->faker->city(),
			'address' => $this->faker->streetAddress(),
			'mobile' => '+48100200300',
			'email' => uniqid() . '@example.com',
			'country' => 'Polska',
			'website' => 'https://example.com',
			'about' => 'restaurant description.'
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
