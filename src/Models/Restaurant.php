<?php

namespace Cartelo\Models;

use Cartelo\Models\Area;
use Cartelo\Models\Day;
use Cartelo\Models\Mobile;
use Cartelo\Models\Social;
use Database\Factories\RestaurantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected $casts = [
		'created_at' => 'datetime',
	];

	protected static function newFactory()
	{
		return RestaurantFactory::new();
	}

	public function areas()
	{
		return $this->hasMany(Area::class);
	}

	public function socials()
	{
		return $this->hasMany(Social::class);
	}

	public function mobiles()
	{
		return $this->hasMany(Mobile::class);
	}

	public function days()
	{
		return $this->hasMany(Day::class);
	}

	public function today()
	{
		return $this->hasOne(Day::class)->where('number', date('N', time()));
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
