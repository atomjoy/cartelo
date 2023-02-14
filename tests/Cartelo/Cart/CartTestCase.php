<?php

namespace Tests\Cartelo\Cart;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class CartTestCase extends TestCase
{
	protected static bool $setUpHasRunOnce = false;

	function __construct()
	{
		parent::__construct();
	}

	protected function setUp(): void
	{
		parent::setUp();

		if (!static::$setUpHasRunOnce) {
			Artisan::call('migrate:fresh --seed --seeder=CarteloSeeder');
			static::$setUpHasRunOnce = true;
		}
	}
}
