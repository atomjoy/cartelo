<?php

namespace Cartelo\Models;

use Cartelo\Models\AddonGroup;
use Database\Factories\AddonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return AddonFactory::new();
	}

	public function groups()
	{
		return $this->belongsToMany(AddonGroup::class);
	}

	// Scope a query to only include visible items.
	public function scopeVisible($query)
	{
		$query->where('visible', 1);
	}

	protected function serializeDate(\DateTimeInterface $date)
	{
		return $date->format($this->dateFormat);
	}

	function addonPrice()
	{
		return $this->price;
	}
}
