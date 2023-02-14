<?php

namespace Cartelo\Models;

use Cartelo\Models\Product;
use Cartelo\Models\AddonGroup;
use Database\Factories\VariantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return VariantFactory::new();
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function groups()
	{
		return $this->belongsToMany(AddonGroup::class);
	}

	// Get group addons ids
	public function getGroupsIdAttribute()
	{
		return $this->groups->pluck('id')->toArray();
	}

	// Scope a query to only include visible variants.
	public function scopeVisible($query)
	{
		$query->where('visible', 1);
	}

	protected function serializeDate(\DateTimeInterface $date)
	{
		return $date->format($this->dateFormat);
	}
}
