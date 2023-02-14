<?php

namespace Cartelo\Traits;

trait HasStripTags
{
	protected $keysWithoutEntities = ['polygon'];

	function stripTags($arr)
	{
		if (config('cartelo.striptags', true) == true) {
			$arr = $this->clearTags($arr);
		}

		if (config('cartelo.htmlentities', true) == true) {
			$arr = $this->clearEntities($arr);
		}

		return $arr;
	}

	function clearTags($arr)
	{
		array_walk_recursive($arr, function (&$v, $k) {
			$v = trim(strip_tags($v));
		});

		return $arr;
	}

	function clearEntities($arr)
	{
		array_walk_recursive($arr, function (&$v, $k) {
			if (!in_array($k, $this->keysWithoutEntities)) {
				$v = htmlentities($v, ENT_QUOTES, "UTF-8");
			}
		});

		return $arr;
	}
}
