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
			'country' => 'Polska',
			'mobile' => '+48100200300',
			'email' => uniqid() . '@example.com',
			'country' => 'Polska',
			'website' => 'https://example.com',
			'about' => 'restaurant description.',
			'on_pay_money' => 1,
			'on_pay_card' => 0,
			'on_pay_online' => 0,
			'on_break' => 0,
			'on_delivery' => 1,
			'delivery_home' => 1,
			'delivery_pickup' => 0,
			'delivery_restaurant' => 0,
			'lng' => '0.000000',
			'lat' => '0.000000',
			'invoice_company' => 'Compoany Ltd.',
			'invoice_country' => 'Polska',
			'invoice_city' => 'Warszawa',
			'invoice_street' => 'Piękna 19',
			'invoice_zip' => '00-000',
			'invoice_nip' => '7771114499',
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
