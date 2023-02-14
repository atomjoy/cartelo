<?php

namespace Cartelo\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cartelo\Models\CartVariant;
use Cartelo\Models\Addon;

class CartVariantAddon extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $hidden = [
		'updated_at',
		'deleted_at',
	];

	protected $casts = [
		'created_at' => 'datetime:Y-m-d',
	];

	function addon()
	{
		return $this->belongsTo(Addon::class);
	}
}
