<?php

namespace Cartelo\Facades;

use Illuminate\Support\Facades\Facade;

class Cartelo extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'cartelo';
	}
}
