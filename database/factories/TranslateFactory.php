<?php

namespace Database\Factories;

use Cartelo\Models\Translate;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslateFactory extends Factory
{
	protected $model = Translate::class;

	public function definition()
	{
		return [
			'locale' => 'pl',
			'key' => $this->faker->sentence(),
			'value' => $this->faker->sentence(),
		];
	}

	// Indicate that the area is visible.
	public function locale($locale)
	{
		return $this->state(function (array $attributes) use ($locale) {
			return [
				'locale' => $locale,
			];
		});
	}
}
