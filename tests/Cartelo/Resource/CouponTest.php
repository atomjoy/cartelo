<?php

namespace Tests\Cartelo\Resource;

use App\Models\User;
use Cartelo\Models\Coupon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
	/** @test */
	function store_user_not_allowed()
	{
		Artisan::call('migrate:fresh');

		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.store'));
		// Data
		$data = Coupon::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.coupons.store'), $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('coupons', $data);
	}

	/** @test */
	function store_worker()
	{
		Artisan::call('migrate:fresh');

		// Worker allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.store'));
		// Data
		$data = Coupon::factory()->make(['user_id' => 1])->toArray();
		// Create
		$res = $this->postJson(route('cartelo.coupons.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The coupon has been created']);
		$this->assertDatabaseHas('coupons', $data);
	}

	/** @test */
	function store_admin()
	{
		Artisan::call('migrate:fresh');

		// Admin allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.store'));
		// Data
		$data = Coupon::factory()->make(['user_id' => 1])->toArray();
		// Create
		$res = $this->postJson(route('cartelo.coupons.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The coupon has been created']);
		$this->assertDatabaseHas('coupons', $data);
	}

	/** @test */
	function index_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.index'));
		// Add
		Coupon::factory()->create();
		// Show list
		$res = $this->getJson('cartelo/coupons');
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
	}

	/** @test */
	function index_worker()
	{
		// User
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.index'));
		// Add
		Coupon::factory()->create();
		// Show list
		$res = $this->getJson('cartelo/coupons');
		$res->assertStatus(200)->assertJsonStructure(['data' => ['coupons']]);
		// Count coupons
		$this->assertDatabaseCount('coupons', 3);
	}

	/** @test */
	function index_admin()
	{
		// User
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.index'));
		// Add
		Coupon::factory()->create();
		// Show list
		$res = $this->getJson('cartelo/coupons');
		$res->assertStatus(200)->assertJsonStructure(['data' => ['coupons']]);
		// Count coupons
		$this->assertDatabaseCount('coupons', 4);
	}

	/** @test */
	function show_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.show'));
		// Get
		$o = Coupon::first();
		// Show
		$res = $this->getJson('cartelo/coupons/' . $o->id);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
	}

	/** @test */
	function show_worker()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.show'));
		// Get
		$o = Coupon::first();
		// Show
		$res = $this->getJson('cartelo/coupons/' . $o->id);
		$res->assertStatus(200)->assertJsonStructure(['data' => ['id']])->assertJson(['data' => ['id' => $o->id]]);
	}

	/** @test */
	function show_admin()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.show'));
		// Get
		$o = Coupon::first();
		// Show
		$res = $this->getJson('cartelo/coupons/' . $o->id);
		$res->assertStatus(200)->assertJsonStructure(['data' => ['id']])->assertJson(['data' => ['id' => $o->id]]);
	}

	/** @test */
	function update_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.update'));
		// Get
		$o = Coupon::first();
		// Data
		// ['name' => $o->name . ' Updated', 'price' => 1.23]
		$data = Coupon::factory()->make(['user_id' => 1])->toArray();
		// Update
		$res = $this->putJson('/cartelo/coupons/' . $o->id, $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('coupons', $data);
	}

	/** @test */
	function update_worker()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.update'));
		// Get
		$o = Coupon::first();
		// Data
		$data = Coupon::factory()->make(['user_id' => 1])->toArray();
		// Update
		$res = $this->putJson('/cartelo/coupons/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The coupon has been updated']);
		$this->assertDatabaseHas('coupons', $data);
	}

	/** @test */
	function update_admin()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.update'));
		// Get
		$o = Coupon::first();
		// Data
		$data = Coupon::factory()->make(['user_id' => 1])->toArray();
		// Update
		$res = $this->putJson('/cartelo/coupons/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The coupon has been updated']);
		$this->assertDatabaseHas('coupons', $data);
	}

	/** @test */
	function destroy_user_not_allowed()
	{
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.destroy'));
		// Create
		$o = Coupon::factory()->create();
		// Check
		$this->assertDatabaseHas('coupons', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/coupons/' . $o->id);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		// Exists
		$this->assertDatabaseHas('coupons', $o->toArray());
	}

	/** @test */
	function destroy_worker()
	{
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.destroy'));
		// Create
		$o = Coupon::factory()->create();
		// Check
		$this->assertDatabaseHas('coupons', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/coupons/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The coupon has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Coupon::findOrFail($o->id);
	}

	/** @test */
	function destroy_admin()
	{
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.coupons.destroy'));
		// Create
		$o = Coupon::factory()->create();
		// Check
		$this->assertDatabaseHas('coupons', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/coupons/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The coupon has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Coupon::findOrFail($o->id);
	}
}
