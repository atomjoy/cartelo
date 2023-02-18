<?php

namespace Tests\Cartelo\Resource;

use App\Models\User;
use Cartelo\Models\Mobile;
use Cartelo\Models\Restaurant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileTest extends TestCase
{
	/** @test */
	function store_user_not_allowed()
	{
		Artisan::call('migrate:fresh');

		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.store'));
		// Data
		$data = Mobile::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.mobiles.store'), $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('mobiles', $data);
	}

	/** @test */
	function store_worker()
	{
		Artisan::call('migrate:fresh');

		// Restaurant
		Restaurant::factory()->create();

		// Worker allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.store'));
		// Data
		$data = Mobile::factory()->make(['restaurant_id' => 1])->toArray();
		// Create
		$res = $this->postJson(route('cartelo.mobiles.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The mobile has been created']);
		$this->assertDatabaseHas('mobiles', $data);
	}

	/** @test */
	function store_admin()
	{
		Artisan::call('migrate:fresh');

		// Restaurant
		Restaurant::factory()->create();

		// Admin allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.store'));
		// Data
		$data = Mobile::factory()->make(['restaurant_id' => 1])->toArray();
		// Create
		$res = $this->postJson(route('cartelo.mobiles.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The mobile has been created']);
		$this->assertDatabaseHas('mobiles', $data);
	}

	/** @test */
	function index_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.index'));
		// Show list
		$res = $this->getJson('cartelo/mobiles');
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
	}

	/** @test */
	function index_worker()
	{
		// User
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.index'));
		// Show list
		$res = $this->getJson('cartelo/mobiles');
		$res->assertStatus(200)->assertJsonStructure(['data' => ['mobiles']]);
		// Count mobiles
		$this->assertDatabaseCount('mobiles', 1);
	}

	/** @test */
	function index_admin()
	{
		// User
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.index'));
		// Show list
		$res = $this->getJson('cartelo/mobiles');
		$res->assertStatus(200)->assertJsonStructure(['data' => ['mobiles']]);
		// Count mobiles
		$this->assertDatabaseCount('mobiles', 1);
	}

	/** @test */
	function show_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.show'));
		// Get
		$o = Mobile::first();
		// Show
		$res = $this->getJson('cartelo/mobiles/' . $o->id);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
	}

	/** @test */
	function show_worker()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.show'));
		// Get
		$o = Mobile::first();
		// Show
		$res = $this->getJson('cartelo/mobiles/' . $o->id);
		$res->assertStatus(200)->assertJsonStructure(['data' => ['id']])->assertJson(['data' => ['id' => $o->id]]);
	}

	/** @test */
	function show_admin()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.show'));
		// Get
		$o = Mobile::first();
		// Show
		$res = $this->getJson('cartelo/mobiles/' . $o->id);
		$res->assertStatus(200)->assertJsonStructure(['data' => ['id']])->assertJson(['data' => ['id' => $o->id]]);
	}

	/** @test */
	function update_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.update'));
		// Get
		$o = Mobile::first();
		// Data
		// ['name' => $o->name . ' Updated', 'price' => 1.23]
		$data = Mobile::factory()->make(['restaurant_id' => 1])->toArray();
		// Update
		$res = $this->putJson('/cartelo/mobiles/' . $o->id, $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('mobiles', $data);
	}

	/** @test */
	function update_worker()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.update'));
		// Get
		$o = Mobile::first();
		// Data
		$data = Mobile::factory()->make(['restaurant_id' => 1, 'number' => '123456789'])->toArray();
		// Update
		$res = $this->putJson('/cartelo/mobiles/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The mobile has been updated']);
		$this->assertDatabaseHas('mobiles', $data);
	}

	/** @test */
	function update_admin()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.update'));
		// Get
		$o = Mobile::first();
		// Data
		$data = Mobile::factory()->make(['restaurant_id' => 1, 'number' => '123456789'])->toArray();
		// Update
		$res = $this->putJson('/cartelo/mobiles/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The mobile has been updated']);
		$this->assertDatabaseHas('mobiles', $data);
	}

	/** @test */
	function destroy_user_not_allowed()
	{
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.destroy'));
		// Create
		$o = Mobile::factory()->create(['restaurant_id' => 1]);
		// Check
		$this->assertDatabaseHas('mobiles', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/mobiles/' . $o->id);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		// Exists
		$this->assertDatabaseHas('mobiles', $o->toArray());
	}

	/** @test */
	function destroy_worker()
	{
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.destroy'));
		// Create
		$o = Mobile::factory()->create(['restaurant_id' => 1]);
		// Check
		$this->assertDatabaseHas('mobiles', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/mobiles/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The mobile has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Mobile::findOrFail($o->id);
	}

	/** @test */
	function destroy_admin()
	{
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.mobiles.destroy'));
		// Create
		$o = Mobile::factory()->create(['restaurant_id' => 1]);
		// Check
		$this->assertDatabaseHas('mobiles', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/mobiles/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The mobile has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Mobile::findOrFail($o->id);
	}
}
