<?php

namespace Tests\Cartelo\Model;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AddonsTest extends TestCase
{
	// use RefreshDatabase;

	/** @test */
	function addons_list()
	{
		Artisan::call('migrate:fresh --seed --seeder=CarteloSeeder');

		$res1 = $this->getJson('cartelo/addons');
		$res1->assertStatus(200)->assertJsonStructure(['data' => ['addons']]);

		$res2 = $this->getJson('cartelo/addons/1');
		$res2->assertStatus(200)->assertJsonStructure(['data' => ['id']]);

		// print_r($res1);
		// print_r($res2);
	}
}
