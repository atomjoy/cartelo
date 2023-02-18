<?php

namespace Tests\Cartelo\Resource;

use App\Models\User;
use Cartelo\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
	/** @test */
	function store_user_not_allowed()
	{
		Artisan::call('migrate:fresh');

		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.categories.store'));
		// Data
		$data = Category::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.categories.store'), $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('categories', $data);
	}

	/** @test */
	function store_worker()
	{
		Artisan::call('migrate:fresh');

		// Worker allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.categories.store'));
		// Data
		$data = Category::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.categories.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The category has been created']);
		$this->assertDatabaseHas('categories', $data);
	}

	/** @test */
	function store_admin()
	{
		Artisan::call('migrate:fresh');

		// Admin allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.categories.store'));
		// Data
		$data = Category::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.categories.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The category has been created']);
		$this->assertDatabaseHas('categories', $data);
	}

	/** @test */
	function index()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.categories.index'));
		// Add
		Category::factory()->create();
		// Show list
		$res = $this->getJson('cartelo/categories');
		$res->assertStatus(200)->assertJsonStructure(['data' => ['categories']]);
		// Count categories
		$this->assertDatabaseCount('categories', 2);
	}

	/** @test */
	function show()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.categories.show'));
		// Get
		$o = Category::first();
		// Show
		$res = $this->getJson('cartelo/categories/' . $o->id);
		$res->assertStatus(200)->assertJsonStructure(['data' => ['id']])->assertJson(['data' => ['id' => $o->id]]);
	}

	/** @test */
	function update_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.categories.update'));
		// Get
		$o = Category::first();
		// Data
		// ['name' => $o->name . ' Updated', 'price' => 1.23]
		$data = Category::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/categories/' . $o->id, $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('categories', $data);
	}

	/** @test */
	function update_worker()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.categories.update'));
		// Get
		$o = Category::first();
		// Data
		$data = Category::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/categories/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The category has been updated']);
		$this->assertDatabaseHas('categories', $data);
	}

	/** @test */
	function update_admin()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.categories.update'));
		// Get
		$o = Category::first();
		// Data
		$data = Category::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/categories/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The category has been updated']);
		$this->assertDatabaseHas('categories', $data);
	}

	/** @test */
	function destroy_user_not_allowed()
	{
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.categories.destroy'));
		// Create
		$o = Category::factory()->create();
		// Check
		$this->assertDatabaseHas('categories', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/categories/' . $o->id);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		// Exists
		$this->assertDatabaseHas('categories', $o->toArray());
	}

	/** @test */
	function destroy_worker()
	{
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.categories.destroy'));
		// Create
		$o = Category::factory()->create();
		// Check
		$this->assertDatabaseHas('categories', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/categories/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The category has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Category::findOrFail($o->id);
	}

	/** @test */
	function destroy_admin()
	{
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.categories.destroy'));
		// Create
		$o = Category::factory()->create();
		// Check
		$this->assertDatabaseHas('categories', $o->toArray());
		// Delete
		$res = $this->deleteJson('/cartelo/categories/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The category has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Category::findOrFail($o->id);
	}
}
