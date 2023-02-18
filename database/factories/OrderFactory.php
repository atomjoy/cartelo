<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Cartelo\Models\Order;

class OrderFactory extends Factory
{
	protected $model = Order::class;

	public function definition()
	{
		return [
			'cost' => rand(10, 369) . '.' . rand(11, 99),
			'payment_method' => 'online',
			'payment_gateway' => 'payu',
			'firstname' => $this->faker->name(),
			'lastname' => $this->faker->lastName(),
			'country' => $this->faker->country(),
			'city' => $this->faker->city(),
			'address' => $this->faker->streetAddress(),
			'email' => uniqid() . '@example.com',
			'phone' => $this->faker->numerify('+48#########'),
			'comment' => $this->faker->sentence(),
			'ip' => '127.0.0.1',
			'floor' => rand(0, 6),
			'invoice' => 1,
			'invoice_company' => 'Company XYZ',
			'invoice_country' => $this->faker->country(),
			'invoice_city' => $this->faker->city(),
			'invoice_street' => $this->faker->streetName(),
			'invoice_zip' => '00-000',
			'invoice_nip' => $this->faker->numerify('123#######'),
		];
	}
}
