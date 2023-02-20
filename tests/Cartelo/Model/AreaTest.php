<?php

namespace Tests\Cartelo\Model;

use Cartelo\Models\Area;
use Cartelo\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Webi\Enums\User\UserRole;

class AreaTest extends TestCase
{
	// use RefreshDatabase;

	/** @test */
	function create_area_not_logged_error()
	{
		$res = $this->postJson('/cartelo/areas', []);
		$res->assertStatus(401)->assertJson([
			'message' => 'Unauthenticated.'
		]);
	}

	/** @test */
	function create_area_invalid_role_error()
	{
		$user = User::factory()->create([
			'name' => 'Henio',
			'role' => UserRole::USER
		]);

		$user = User::where('id', $user->id)->first();

		$this->actingAs($user);

		$res = $this->postJson('/cartelo/areas', []);
		$res->assertStatus(401)->assertJson([
			'message' => 'Unauthorized Role.'
		]);
	}

	/** @test */
	function create_area_success()
	{
		$geo_json = Area::geoJsonPolygonSample();

		$id = Restaurant::factory()->create()->id;

		$user = User::factory()->create([
			'name' => 'Henio',
			'role' => UserRole::WORKER
		]);

		$user = User::where('id', $user->id)->first();

		$this->actingAs($user);

		$res = $this->postJson('/cartelo/areas', []);
		$res->assertStatus(422)->assertJson([
			'message' => 'The restaurant id field is required.',
		]);

		$res = $this->postJson('/cartelo/areas', [
			'restaurant_id' => $id
		]);
		$res->assertStatus(422)->assertJson([
			'message' => 'The name field is required.',
		]);

		$res = $this->postJson('/cartelo/areas', [
			'restaurant_id' => $id,
			'name' => '<h1>Food Love</h2> Restaurant'
		]);
		$res->assertStatus(422)->assertJson([
			'message' => 'The about field is required.',
		]);

		$res = $this->postJson('/cartelo/areas', [
			'restaurant_id' => $id,
			'name' => '<h1>Food Love</h2> Restaurant',
			'about' => 'Restauracja ...',
		]);
		$res->assertStatus(422)->assertJson([
			'message' => 'The polygon field is required.',
		]);

		$res = $this->postJson('/cartelo/areas', [
			'restaurant_id' => $id,
			'name' => '<h1>Food Love</h2> Restaurant',
			'about' => 'Restauracja ' . uniqid(),
			'polygon' => $geo_json,
		]);
		$res->assertStatus(200)->assertJson([
			'message' => 'The area has been created',
		]);

		$res = $this->postJson('/cartelo/areas', [
			'restaurant_id' => $id,
			'name' => '<h1>Food Love</h2> Restaurant',
			'about' => 'Restauracja ' . uniqid(),
			'polygon' => $geo_json,
		]);
		$res->assertStatus(422)->assertJson([
			'message' => 'The name has already been taken.',
		]);

		// Update
		$id = Area::first()->id;
		$res = $this->putJson('/cartelo/areas/' . $id, [
			'restaurant_id' => $id,
			'name' => '<h1>Food Love</h2> Restaurant',
			'about' => 'Restauracja ' . uniqid(),
			'polygon' => $geo_json,
		]);
		$res->assertStatus(200)->assertJson([
			'message' => 'The area has been updated',
		]);

		// Delete
		$res = $this->deleteJson('/cartelo/areas/' . $id);
		$res->assertStatus(200)->assertJson([
			'message' => 'The area has been deleted',
		]);

		// Update
		$res = $this->putJson('/cartelo/areas/' . $id, [
			'restaurant_id' => $id,
			'name' => '<h1>Food Love</h2> Restaurant',
			'about' => 'Restauracja ' . uniqid(),
			'polygon' => $geo_json,
		]);
		$res->assertStatus(404)->assertJson([
			'message' => 'Not Found.',
		]);
	}

	/** @test */
	function update_area_only_logged_error()
	{
		// Route not allowed
		$res = $this->putJson('/cartelo/areas/1');
		$res->assertStatus(401)->assertJson([
			'message' => 'Unauthenticated.',
		]);
	}

	/** @test */
	function update_area_error()
	{
		// Role user not allowed
		$user = User::factory()->create([
			'name' => 'Maxio',
			'role' => UserRole::USER
		]);
		$user = User::where('id', $user->id)->first();
		$this->actingAs($user);

		// Seeds
		$area = Area::factory()->count(1)->make();
		$r = Restaurant::factory()->create();
		$r->areas()->saveMany($area);

		// Checks
		$res = $this->putJson('/cartelo/areas/' . $r->areas->first()->id, [
			'name' => 'Wonono',
		]);
		$res->assertStatus(401)->assertJson([
			'message' => 'Unauthorized Role.',
		]);
	}

	/** @test */
	function update_area_success()
	{
		// Logged
		$user = User::factory()->create([
			'name' => 'Henio',
			'role' => UserRole::WORKER
		]);
		$user = User::where('id', $user->id)->first();
		$this->actingAs($user);

		// Vars
		$geo_json = Area::geoJsonPolygonSample();

		// Seeds
		$area = Area::factory()->count(1)->make();
		$r = Restaurant::factory()->create();
		$r->areas()->saveMany($area);

		// Checks
		$res = $this->putJson('/cartelo/areas/' . $r->areas->first()->id, [
			'name' => 'Wonolando',
			'about' => 'About restaurant',
			'polygon' => $geo_json,
			'min_order_cost' => 23.99,
			'cost' => 123.88,
			'visible' => 1,
			'time' => 60,
			'free_from' => 100,
			'on_free_from' => 1,
			'sorting' => 1,
		]);
		$res->assertStatus(200)->assertJson([
			'message' => 'The area has been updated',
		]);

		// Updated
		$this->assertDatabaseHas('areas', [
			'name' => 'Wonolando',
			'about' => 'About restaurant',
			'min_order_cost' => 23.99,
			'cost' => 123.88,
			'visible' => 1,
			'time' => 60,
			'free_from' => 100,
			'on_free_from' => 1,
			'sorting' => 1,
		]);
	}

	/** @test */
	function delete_area_only_logged_error()
	{
		// Route not allowed
		$res = $this->deleteJson('/cartelo/areas/1');
		$res->assertStatus(401)->assertJson([
			'message' => 'Unauthenticated.',
		]);
	}

	/** @test */
	function delete_area_error()
	{
		// Role user not allowed
		$user = User::factory()->create([
			'name' => 'Maxio',
			'role' => UserRole::USER
		]);
		$user = User::where('id', $user->id)->first();
		$this->actingAs($user);

		// Seeds
		$area = Area::factory()->count(2)->make();
		$r = Restaurant::factory()->create();
		$r->areas()->saveMany($area);

		// Delete not allowed
		$res = $this->deleteJson('/cartelo/areas/' . $r->areas->first()->id);
		$res->assertStatus(401)->assertJson([
			'message' => 'Unauthorized Role.',
		]);
	}

	/** @test */
	function delete_area_success()
	{
		// Logged
		$user = User::factory()->create([
			'name' => 'Henio',
			'role' => UserRole::WORKER
		]);
		$user = User::where('id', $user->id)->first();
		$this->actingAs($user);

		// Seeds
		$area = Area::factory()->count(3)->make();
		$r = Restaurant::factory()->create();
		$r->areas()->saveMany($area);

		// Delete
		$res = $this->deleteJson('/cartelo/areas/' . $r->areas->first()->id);
		$res->assertStatus(200)->assertJson([
			'message' => 'The area has been deleted',
		]);

		// Soft Deleted
		// $this->assertNotNull($r->areas->first()->fresh()->deleted_at);
	}
}
