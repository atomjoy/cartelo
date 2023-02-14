<?php

namespace Tests\Cartelo\Model;

use App\Models\AddonGroup;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
	// use RefreshDatabase;

	/** @test */
	function products_list()
	{
		$res = $this->getJson('/cartelo/products');
		$res->assertStatus(200)->assertJsonStructure([
			'data'
		]);
	}
}
