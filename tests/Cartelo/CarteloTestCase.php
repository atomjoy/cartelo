<?php

namespace Tests\Cartelo;

use Tests\TestCase;

class CarteloTestCase extends TestCase
{
	public $data = [];

	function __construct()
	{
		parent::__construct();

		$this->data = [
			'email' => 'user@localhost',
		];

		// Set global variable accesible from all tests
		global $globalArr;
	}

	protected function setUp(): void
	{
		parent::setUp();
	}

	function setEmail($val = 'user@localhost', $name = 'email')
	{
		$this->data[$name] = $val;
	}

	function getEmail($name = 'email')
	{
		return $this->data[$name];
	}

	function setGlobal($val, $name = 'default')
	{
		global $globalArr;
		$globalArr[$name] = $val;
	}

	function getGlobal($name = 'default')
	{
		global $globalArr;
		return $globalArr[$name];
	}

	function getPassword($html)
	{
		preg_match('/word>[a-zA-Z0-9]+<\/pass/', $html, $matches, PREG_OFFSET_CAPTURE);
		return str_replace(['word>', '</pass'], '', end($matches)[0]);
	}
}
