<?php

namespace Cartelo\Models;

use Cartelo\Models\Variant;
use Cartelo\Models\Category;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return ProductFactory::new();
	}

	public function categories()
	{
		return $this->belongsToMany(Category::class)->orderBy('sorting', 'desc')->withTimestamps();
	}

	public function translates()
	{
		return $this->hasMany(Translate::class);
	}

	public function variants()
	{
		return $this->hasMany(Variant::class);
	}

	public function filter_variants()
	{
		return $this->hasMany(Variant::class)->orderBy('sorting', 'asc')->orderBy('id', 'asc');
	}

	public function getCategoriesListAttribute()
	{
		return (array) $this->categories->pluck('id')->toArray();
	}

	public function getOnSaleAttribute()
	{
		if (!empty($this->variants()->first())) {
			return $this->variants()->first()->on_sale;
		}
		return 0;
	}

	public function getPriceAttribute()
	{
		if (!empty($this->variants()->first())) {
			return $this->variants()->first()->price;
		}
		return 0;
	}

	public function getPriceSaleAttribute()
	{
		if (!empty($this->variants()->first())) {
			return $this->variants()->first()->price_sale;
		}
		return 0;
	}

	public function getVariantIdAttribute()
	{
		if (!empty($this->variants()->first())) {
			return $this->variants()->first()->id;
		}
		return 0;
	}

	/**
	 * Scope a query to only include visible products.
	 */
	public function scopeVisible($query)
	{
		$query->where('visible', 1);
	}

	protected function serializeDate(\DateTimeInterface $date)
	{
		return $date->format($this->dateFormat);
	}
}
