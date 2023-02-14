<?php

namespace Cartelo\Models;

use Cartelo\Models\Product;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return CategoryFactory::new();
	}

	public function products()
	{
		return $this->belongsToMany(Product::class)->withTimestamps();
	}

	public function visibleProducts()
	{
		return $this->belongsToMany(Product::class)->where('visible', 1)->orderBy('sorting', 'asc')->orderBy('id', 'asc')->withTimestamps();
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
