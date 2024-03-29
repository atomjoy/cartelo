<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Cartelo\Models\Reward;

class RewardFactory extends Factory
{
	protected $model = Reward::class;

	public function definition()
	{
		return [
			'user_id' => null,
			'order_id' => null,
			'type' => 'plus',
			'points' => $this->faker->randomFloat(),
			'description' => 'Plus points',
			'expired_at' => now()->addDays(366),
		];
	}
}
