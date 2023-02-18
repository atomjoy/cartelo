<?php

namespace Database\Factories;

use Cartelo\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
	protected $model = Product::class;

	public function definition()
	{
		$name = 'Product ' . uniqid();

		return [
			'name' => $name,
			'slug' => Str::slug($name),
			'image' => $this->faker->imageUrl(256, 256, 'food', true),
			'about' => $this->faker->sentence(),
			'on_stock' => 1,
			'sorting' => 0,
			'visible' => 1,
		];
	}

	public function outOfStock()
	{
		return $this->state(function (array $attributes) {
			return [
				'on_stock' => 0,
			];
		});
	}

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
