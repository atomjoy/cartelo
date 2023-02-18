<?php

namespace Database\Factories;

use Cartelo\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

class AreaFactory extends Factory
{
	protected $model = Area::class;

	public function definition()
	{
		return [
			'restaurant_id' => null,
			'name' => 'Area ' . uniqid(),
			'about' => $this->faker->sentence(),
			'cost' => $this->faker->randomFloat(2, 40, 155),
			'min_order_cost' => $this->faker->randomFloat(2, 60, 100),
			'free_from' => 199.99,
			'on_free_from' => 0,
			'time' => 60,
			'polygon' => '{"type": "Polygon", "coordinates": [[[21.01752050781249, 52.16553065086626], [21.018035491943348, 52.12265533376558], [21.079490264892566, 52.12697633873785], [21.06421240234374, 52.143413406069634], [21.052024444580066, 52.154473402050264], [21.043269714355457, 52.15647444111914], [21.032626708984363, 52.16711003359743], [21.01752050781249, 52.16553065086626]]]}',
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
