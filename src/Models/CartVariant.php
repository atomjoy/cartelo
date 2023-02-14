<?php

namespace Cartelo\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cartelo\Models\Variant;
use Cartelo\Models\CartVariantAddon;

class CartVariant extends Model
{
	use HasFactory, SoftDeletes;

	public $incrementing = true;

	protected $guarded = [];

	protected $hidden = [
		'created_at',
		'updated_at'
	];

	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
	];

	function variant()
	{
		return $this->belongsTo(Variant::class);
	}

	function addons()
	{
		return $this->hasMany(CartVariantAddon::class);
	}
}
