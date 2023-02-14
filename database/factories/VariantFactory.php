<?php

namespace Database\Factories;

use Cartelo\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

class VariantFactory extends Factory
{
	protected $model = Variant::class;

	public function definition()
	{
		$price = $this->faker->randomFloat(2, 15, 25);
		$price_sale = $price - $this->faker->randomFloat(2, 2, 5);
		$cash = $price * 0.03;

		return [
			'product_id' => 1,
			'size' => 'Size ' . uniqid(),
			'price' => $price,
			'price_sale' => $price_sale,
			'packaging' => $this->faker->randomFloat(2, 0.5, 2),
			'cashback' => round($cash, 2),
			'on_sale' => 0,
			'sorting' => 0,
		];
	}

	public function onsale()
	{
		return $this->state(function (array $attributes) {
			return [
				'on_sale' => 1,
			];
		});
	}
}
