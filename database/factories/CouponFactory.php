<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Cartelo\Models\Coupon;

class CouponFactory extends Factory
{
	protected $model = Coupon::class;

	public function definition()
	{
		return [
			'user_id' => null,
			'code' => uniqid(),
			'description' => 'Promotion coupon',
			'type' => 'amount',
			'discount' => $this->faker->randomFloat(2, 10, 60),
			'max_order_percent' => 50,
			'active' => 1,
			'expired_at' => now()->addDays(366)->format('Y-m-d H:i:s'),
			'used_at' => now()->subDays(10)->format('Y-m-d H:i:s'),
		];
	}
}
