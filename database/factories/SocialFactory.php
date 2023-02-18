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
			'icon' => 'https://img.icons8.com/ios-glyphs/2x/link.png',
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
