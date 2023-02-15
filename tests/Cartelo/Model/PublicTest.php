<?php

namespace Tests\Cartelo\Model;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PublicTest extends TestCase
{
	// use RefreshDatabase;

	/** @test */
	function fresh_db()
	{
		Artisan::call('migrate:fresh --seed --seeder=CarteloSeeder');

		$this->assertTrue(true);
	}

	/** @test */
	function addons()
	{
		$res1 = $this->getJson('cartelo/addons');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['addons']]);

		$res2 = $this->getJson('cartelo/addons/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function addongroups()
	{
		$res1 = $this->getJson('cartelo/addongroups');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['addongroups']]);

		$res2 = $this->getJson('cartelo/addongroups/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function products()
	{
		$res1 = $this->getJson('cartelo/products');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['products']]);

		$res2 = $this->getJson('cartelo/products/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function variants()
	{
		$res1 = $this->getJson('cartelo/variants');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['variants']]);

		$res2 = $this->getJson('cartelo/variants/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function categories()
	{
		$res1 = $this->getJson('cartelo/categories');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['categories']]);

		$res2 = $this->getJson('cartelo/categories/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function days()
	{
		$res1 = $this->getJson('cartelo/days');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['days']]);

		$res2 = $this->getJson('cartelo/days/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function socials()
	{
		$res1 = $this->getJson('cartelo/socials');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['socials']]);

		$res2 = $this->getJson('cartelo/socials/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function restaurants()
	{
		$res1 = $this->getJson('cartelo/restaurants');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['restaurants']]);

		$res2 = $this->getJson('cartelo/restaurants/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function mobiles()
	{
		$user = User::factory()->create(['role' => 'admin']);
		$this->actingAs($user);

		$res1 = $this->getJson('cartelo/mobiles');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['mobiles']]);

		$res2 = $this->getJson('cartelo/mobiles/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function coupons()
	{
		$user = User::factory()->create(['role' => 'admin']);
		$this->actingAs($user);

		$res1 = $this->getJson('cartelo/coupons');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['coupons']]);

		$res2 = $this->getJson('cartelo/coupons/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}

	/** @test */
	function users()
	{
		$user = User::factory()->create(['role' => 'admin']);
		$this->actingAs($user);

		$res1 = $this->getJson('cartelo/users');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['users']]);

		$res2 = $this->getJson('cartelo/users/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}
}
