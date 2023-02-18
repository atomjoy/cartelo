<?php

namespace Tests\Cartelo\Resource;

use App\Models\User;
use Cartelo\Models\Area;
use Cartelo\Models\Restaurant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AreaTest extends TestCase
{
	/** @test */
	function store_user_not_allowed()
	{
		Artisan::call('migrate:fresh');

		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.areas.store'));
		// Data
		$data = Area::factory()->make()->toArray();
		// Create
		$res = $this->postJson(route('cartelo.areas.store'), $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('areas', $data);
	}

	/** @test */
	function store_worker()
	{
		Artisan::call('migrate:fresh');

		// Worker allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.areas.store'));
		// Data
		$r = Restaurant::factory()->create();
		$data = Area::factory()->make(['restaurant_id' => $r->id])->toArray();
		$data['polygon'] = Area::geoJsonPolygonSample();
		// Create
		$res = $this->postJson('/cartelo/areas', $data);
		$res->assertStatus(200)->assertJson(['message' => 'The area has been created']);
		unset($data['polygon']);
		$this->assertDatabaseHas('areas', $data);
	}

	/** @test */
	function store_admin()
	{
		Artisan::call('migrate:fresh');

		// Admin allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.areas.store'));
		// Data
		$r = Restaurant::factory()->create();
		$data = Area::factory()->make(['restaurant_id' => $r->id])->toArray();
		$data['polygon'] = Area::geoJsonPolygonSample();
		// Create
		$res = $this->postJson(route('cartelo.areas.store'), $data);
		$res->assertStatus(200)->assertJson(['message' => 'The area has been created']);
		unset($data['polygon']);
		$this->assertDatabaseHas('areas', $data);
	}

	/** @test */
	function index()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.areas.index'));
		// Add
		Area::factory()->create();
		// Show list
		$res = $this->getJson('cartelo/areas');
		$res->assertStatus(200)->assertJsonStructure(['data' => ['areas']]);
		// Count areas
		$this->assertDatabaseCount('areas', 2);
	}

	/** @test */
	function show()
	{
		// Url
		$this->assertTrue(Route::has('cartelo.areas.show'));
		// Get
		$o = Area::first();
		// Show
		$res = $this->getJson('cartelo/areas/' . $o->id);
		$res->assertStatus(200)->assertJsonStructure(['data' => ['id']])->assertJson(['data' => ['id' => $o->id]]);
	}

	/** @test */
	function update_user_not_allowed()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.areas.update'));
		// Get
		$o = Area::first();
		// Data
		// ['name' => $o->name . ' Updated', 'price' => 1.23]
		$data = Area::factory()->hidden()->sorting(9)->make()->toArray();
		// Update
		$res = $this->putJson('/cartelo/areas/' . $o->id, $data);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		$this->assertDatabaseMissing('areas', $data);
	}

	/** @test */
	function update_worker()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.areas.update'));
		// Get
		$o = Area::first();
		// Data
		$r = Restaurant::factory()->create();
		$data = Area::factory()->hidden()->sorting(9)->make(['restaurant_id' => $r->id])->toArray();
		$data['polygon'] = Area::geoJsonPolygonSample();
		// Update
		$res = $this->putJson('/cartelo/areas/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The area has been updated']);
		unset($data['polygon']);
		$this->assertDatabaseHas('areas', $data);
	}

	/** @test */
	function update_admin()
	{
		// User not allowed
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.areas.update'));
		// Get
		$o = Area::first();
		// Data
		$r = Restaurant::factory()->create();
		$data = Area::factory()->hidden()->sorting(9)->make(['restaurant_id' => $r->id])->toArray();
		$data['polygon'] = Area::geoJsonPolygonSample();
		// Update
		$res = $this->putJson('/cartelo/areas/' . $o->id, $data);
		$res->assertStatus(200)->assertJson(['message' => 'The area has been updated']);
		unset($data['polygon']);
		$this->assertDatabaseHas('areas', $data);
	}

	/** @test */
	function destroy_user_not_allowed()
	{
		$user = User::factory()->create(['role' => 'user', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.areas.destroy'));
		// Create
		$o = Area::factory()->create();
		// Check
		$data = $o->toArray();
		unset($data['polygon']);
		$this->assertDatabaseHas('areas', $data);
		// Delete
		$res = $this->deleteJson('/cartelo/areas/' . $o->id);
		$res->assertStatus(401)->assertJson(['message' => 'Unauthorized Role.']);
		// Exists
		$this->assertDatabaseHas('areas', $data);
	}

	/** @test */
	function destroy_worker()
	{
		$user = User::factory()->create(['role' => 'worker', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.areas.destroy'));
		// Create
		$o = Area::factory()->create();
		// Check
		$data = $o->toArray();
		unset($data['polygon']);
		$this->assertDatabaseHas('areas', $data);
		// Delete
		$res = $this->deleteJson('/cartelo/areas/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The area has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Area::findOrFail($o->id);
	}

	/** @test */
	function destroy_admin()
	{
		$user = User::factory()->create(['role' => 'admin', 'username' => 'user' . uniqid()]);
		$this->actingAs($user);

		// Url
		$this->assertTrue(Route::has('cartelo.areas.destroy'));
		// Create
		$o = Area::factory()->create();
		// Check
		$data = $o->toArray();
		unset($data['polygon']);
		$this->assertDatabaseHas('areas', $data);
		// Delete
		$res = $this->deleteJson('/cartelo/areas/' . $o->id);
		$res->assertStatus(200)->assertJson(['message' => 'The area has been deleted']);
		// Missing
		$this->expectException(ModelNotFoundException::class);
		Area::findOrFail($o->id);
	}
}
