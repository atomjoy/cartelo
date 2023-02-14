<?php

namespace Tests\Cartelo\Model;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase
{
	// use RefreshDatabase;

	/** @test */
	function login_route_exists()
	{
		$this->assertTrue(Route::has('login'));
	}
}
