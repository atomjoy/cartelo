<?php

namespace Cartelo;

use Cartelo\Service\CarteloService;
use Cartelo\Interfaces\CarteloInterface;

// Class for facade
class Cartelo
{
	protected $url;

	public function show(CarteloInterface $obj)
	{
		return (new CarteloService())->check($obj);
	}
}
