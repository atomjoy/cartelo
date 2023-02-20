<?php

namespace Cartelo\Models;

use Database\Factories\TranslateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Trans\Models\Translate as Trans;

class Translate extends Trans
{
	use HasFactory;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return TranslateFactory::new();
	}
}
