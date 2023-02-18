<?php

namespace Tests\Cartelo\Resource;

use App\Models\User;
use Cartelo\Models\Addon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddonTest extends TestCase
{
	/** @test */
	function store_user_not_allowed()
	{
		Artisan::call('migrate:fresh');

		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addons.store'));
		// Data
		$data = Addon::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.addons.store'), $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('addons', $data);
	}

	/** @test */
	function store_worker()
	{
		Artisan::call('migrate:fresh');

		// Worker allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addons.store'));
		// Data
		$data = Addon::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.addons.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The addon has been created']);
		$this->assertDatabaseHas('addons', $data);
	}

	/** @test */
	function store_admin()
	{
		Artisan::call('migrate:fresh');

		// Admin allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addons.store'));
		// Data
		$data = Addon::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.addons.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The addon has been created']);
		$this->assertDatabaseHas('addons', $data);
	}

	/** @test */
	function index()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.addons.index'));
		// Add
		Addon::factory()->create();
		// Show list
		$res = $this->getJson('cartelo/addons');
		$res->assertStatus(200)->assertJsonStructure(['data' => ['addons']]);
		// Count addons
		$this->assertDatabaseCount('addons', 2);
	}

	/** @test */
	function show()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.addons.show'));
		// Get
		$o = Addon::first();
		// Show
		$res = $this->getJson('cartelo/addons/' . $o->id);
		$res->assertStatus(200)->assertJsonStructure(['data' => ['id']])->assertJson(['data' => ['id' => $o->id]]);
	}

	/** @test */
	function update_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addons.update'));
		// Get
		$o = Addon::first();
		// Data
		// ['name' => $o->name . ' Updated', 'price' => 1.23]
		$data = Addon::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/addons/' . $o->id, $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('addons', $data);
	}

	/** @test */
	function update_worker()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addons.update'));
		// Get
		$o = Addon::first();
		// Data
		$data = Addon::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/addons/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The addon has been updated']);
		$this->assertDatabaseHas('addons', $data);
	}

	/** @test */
	function update_admin()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addons.update'));
		// Get
		$o = Addon::first();
		// Data
		$data = Addon::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/addons/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The addon has been updated']);
		$this->assertDatabaseHas('addons', $data);
	}

	/** @test */
	function destroy_user_not_allowed()
	{
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addons.destroy'));
		// Create
		$o = Addon::factory()->create();
		// Check
		$this->assertDatabaseHas('addons', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/addons/' . $o->id);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		// Exists
		$this->assertDatabaseHas('addons', $o->toArray());
	}

	/** @test */
	function destroy_worker()
	{
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addons.destroy'));
		// Create
		$o = Addon::factory()->create();
		// Check
		$this->assertDatabaseHas('addons', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/addons/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The addon has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Addon::findOrFail($o->id);
	}

	/** @test */
	function destroy_admin()
	{
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addons.destroy'));
		// Create
		$o = Addon::factory()->create();
		// Check
		$this->assertDatabaseHas('addons', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/addons/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The addon has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Addon::findOrFail($o->id);
	}
}
