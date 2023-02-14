<?php

namespace Database\Factories;

use Cartelo\Models\Social;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialFactory extends Factory
{
	protected $model = Social::class;

	public function definition()
	{
		return [
			'restaurant_id' => null,
			'name' => 'Social ' . uniqid(),
			'link' => 'https://youtube.com',
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
