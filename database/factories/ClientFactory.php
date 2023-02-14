<?php

namespace Database\Factories;

use Cartelo\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
	protected $model = Client::class;

	public function definition()
	{
		// $email = $this->faker->unique()->safeEmail();
		// $email = uniqid().'@'.request()->getHttpHost();

		return [
			'firstname' => $this->faker->name(),
			'lastname' => $this->faker->lastName(),
			'country' => $this->faker->country(),
			'city' => $this->faker->city(),
			'address' => $this->faker->streetAddress(),
			'email' => uniqid() . '@localhost',
			'mobile' => $this->faker->numerify('+48#########'),
			'comment' => $this->faker->sentence(),
			'ip' => '127.0.0.1',
			'floor' => rand(0, 6),
		];
	}
}
