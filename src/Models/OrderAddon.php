<?php

namespace Cartelo\Models;

use Cartelo\Models\Addon;
use Cartelo\Models\OrderVariant;
use Database\Factories\OrderAddonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAddon extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return OrderAddonFactory::new();
	}

	public function addon()
	{
		return $this->belongsTo(Addon::class);
	}

	public function variant()
	{
		return $this->belongsTo(OrderVariant::class);
	}

	protected function serializeDate(\DateTimeInterface $date)
	{
		return $date->format($this->dateFormat);
	}
}
