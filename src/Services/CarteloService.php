<?php

namespace Cartelo\Services;

use Cartelo\Interfaces\CarteloInterface;

class CarteloService
{
	function check(CarteloInterface $obj)
	{
		return $obj->show();
	}
}
