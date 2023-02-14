<?php

namespace Tests\Cartelo;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class MigrateTestCase extends TestCase
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
			Artisan::call('migrate:fresh');
			static::$setUpHasRunOnce = true;
		}
	}
}
