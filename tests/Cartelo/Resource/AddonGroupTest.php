<?php

namespace Tests\Cartelo\Resource;

use App\Models\User;
use Cartelo\Models\AddonGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddonGroupTest extends TestCase
{
	/** @test */
	function store_user_not_allowed()
	{
		Artisan::call('migrate:fresh');

		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.store'));
		// Data
		$data = AddonGroup::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.addongroups.store'), $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('addon_groups', $data);
	}

	/** @test */
	function store_worker()
	{
		Artisan::call('migrate:fresh');

		// Worker allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.store'));
		// Data
		$data = AddonGroup::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.addongroups.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The addongroup has been created']);
		$this->assertDatabaseHas('addon_groups', $data);
	}

	/** @test */
	function store_admin()
	{
		Artisan::call('migrate:fresh');

		// Admin allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.store'));
		// Data
		$data = AddonGroup::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.addongroups.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The addongroup has been created']);
		$this->assertDatabaseHas('addon_groups', $data);
	}

	/** @test */
	function index()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.index'));
		// Add
		AddonGroup::factory()->create();
		// Show list
		$res = $this->getJson('cartelo/addongroups');
		$res->assertStatus(200)->assertJsonStructure(['data' => ['addongroups']]);
		// Count addongroups
		$this->assertDatabaseCount('addon_groups', 2);
	}

	/** @test */
	function show()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.show'));
		// Get
		$o = AddonGroup::first();
		// Show
		$res = $this->getJson('cartelo/addongroups/' . $o->id);
		$res->assertStatus(200)->assertJsonStructure(['data' => ['id']])->assertJson(['data' => ['id' => $o->id]]);
	}

	/** @test */
	function update_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.update'));
		// Get
		$o = AddonGroup::first();
		// Data
		// ['name' => $o->name . ' Updated', 'price' => 1.23]
		$data = AddonGroup::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/addongroups/' . $o->id, $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('addon_groups', $data);
	}

	/** @test */
	function update_worker()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.update'));
		// Get
		$o = AddonGroup::first();
		// Data
		$data = AddonGroup::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/addongroups/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The addongroup has been updated']);
		$this->assertDatabaseHas('addon_groups', $data);
	}

	/** @test */
	function update_admin()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.update'));
		// Get
		$o = AddonGroup::first();
		// Data
		$data = AddonGroup::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/addongroups/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The addongroup has been updated']);
		$this->assertDatabaseHas('addon_groups', $data);
	}

	/** @test */
	function destroy_user_not_allowed()
	{
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.destroy'));
		// Create
		$o = AddonGroup::factory()->create();
		// Check
		$this->assertDatabaseHas('addon_groups', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/addongroups/' . $o->id);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		// Exists
		$this->assertDatabaseHas('addon_groups', $o->toArray());
	}

	/** @test */
	function destroy_worker()
	{
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.destroy'));
		// Create
		$o = AddonGroup::factory()->create();
		// Check
		$this->assertDatabaseHas('addon_groups', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/addongroups/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The addongroup has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		AddonGroup::findOrFail($o->id);
	}

	/** @test */
	function destroy_admin()
	{
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.addongroups.destroy'));
		// Create
		$o = AddonGroup::factory()->create();
		// Check
		$this->assertDatabaseHas('addon_groups', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/addongroups/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The addongroup has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		AddonGroup::findOrFail($o->id);
	}
}
