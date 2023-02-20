<?php

namespace Tests\Cartelo\Resource;

use App\Models\User;
use Cartelo\Models\Translate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslateTest extends TestCase
{
	/** @test */
	function store_user_not_allowed()
	{
		Artisan::call('migrate:fresh');

		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.translates.store'));
		// Data
		$data = Translate::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.translates.store'), $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('translates', $data);
	}

	/** @test */
	function store_worker()
	{
		Artisan::call('migrate:fresh');

		// Worker allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.translates.store'));
		// Data
		$data = Translate::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.translates.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The translate has been created']);
		$this->assertDatabaseHas('translates', $data);
	}

	/** @test */
	function store_admin()
	{
		Artisan::call('migrate:fresh');

		// Admin allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.translates.store'));
		// Data
		$data = Translate::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.translates.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The translate has been created']);
		$this->assertDatabaseHas('translates', $data);
	}

	/** @test */
	function index()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.translates.index'));
		// Add
		Translate::factory()->create();
		// Show list
		$res = $this->getJson('cartelo/translates');
		$res->assertStatus(200)->assertJsonStructure(['data' => ['translates']]);
		// Count translates
		$this->assertDatabaseCount('translates', 2);
	}

	/** @test */
	function show()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.translates.show'));
		// Get
		$o = Translate::first();
		// Show
		$res = $this->getJson('cartelo/translates/' . $o->id);
		$res->assertStatus(200)->assertJsonStructure(['data' => ['id']])->assertJson(['data' => ['id' => $o->id]]);
	}

	/** @test */
	function update_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.translates.update'));
		// Get
		$o = Translate::first();
		// Data
		// ['name' => $o->name . ' Updated', 'price' => 1.23]
		$data = Translate::factory()->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/translates/' . $o->id, $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('translates', $data);
	}

	/** @test */
	function update_worker()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.translates.update'));
		// Get
		$o = Translate::first();
		// Data
		$data = Translate::factory()->locale('de')->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/translates/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The translate has been updated']);
		$this->assertDatabaseHas('translates', $data);
	}

	/** @test */
	function update_admin()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.translates.update'));
		// Get
		$o = Translate::first();
		// Data
		$data = Translate::factory()->locale('es')->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/translates/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The translate has been updated']);
		$this->assertDatabaseHas('translates', $data);
	}

	/** @test */
	function destroy_user_not_allowed()
	{
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.translates.destroy'));
		// Create
		$o = Translate::factory()->create();
		// Check
		$this->assertDatabaseHas('translates', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/translates/' . $o->id);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		// Exists
		$this->assertDatabaseHas('translates', $o->toArray());
	}

	/** @test */
	function destroy_worker()
	{
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.translates.destroy'));
		// Create
		$o = Translate::factory()->create();
		// Check
		$this->assertDatabaseHas('translates', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/translates/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The translate has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Translate::findOrFail($o->id);
	}

	/** @test */
	function destroy_admin()
	{
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.translates.destroy'));
		// Create
		$o = Translate::factory()->create();
		// Check
		$this->assertDatabaseHas('translates', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/translates/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The translate has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Translate::findOrFail($o->id);
	}
}
