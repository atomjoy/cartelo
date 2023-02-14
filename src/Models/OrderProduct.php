<?php

namespace Cartelo\Models;

use Cartelo\Models\Variant;
use Cartelo\Models\OrderAddon;
use Database\Factories\OrderProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return OrderProductFactory::new();
	}

	public function variant()
	{
		return $this->belongsTo(Variant::class);
	}

	public function addons()
	{
		return $this->hasMany(OrderAddon::class);
	}

	protected function serializeDate(\DateTimeInterface $date)
	{
		return $date->format($this->dateFormat);
	}
}
