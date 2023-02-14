<?php

namespace Database\Factories;

use Cartelo\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
	protected $model = Category::class;

	public function definition()
	{
		return [
			'name' => 'Category ' . uniqid(),
			'slug' => Str::slug('Category ' . uniqid()),
			'about' => $this->faker->sentence(),
			'image_url' => 'https://invalid.image.url/' . uniqid() . '.png',
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
}
