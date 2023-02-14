<?php

namespace Cartelo\Models;

use Cartelo\Models\Addon;
use Cartelo\Models\Variant;
use Database\Factories\AddonGroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonGroup extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return AddonGroupFactory::new();
	}

	public function addons()
	{
		return $this->belongsToMany(Addon::class);
	}

	public function variants()
	{
		return $this->belongsToMany(Variant::class);
	}

	//Get group addons id group_addons
	public function getAddonsIdAttribute()
	{
		return $this->addons->pluck('id')->toArray();
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
}
