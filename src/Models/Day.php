<?php

namespace Cartelo\Models;

use Cartelo\Models\Restaurant;
use Database\Factories\DayFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Day extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $hidden = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
	];

	protected static function newFactory()
	{
		return DayFactory::new();
	}

	function restaurant()
	{
		return $this->belongsTo(Restaurant::class);
	}

	function getOpenHourAttribute()
	{
		return $this->open->format('H:i');
	}

	function getCloseHourAttribute()
	{
		return $this->close->format('H:i');
	}

	/**
	 * Determines if the restaurant delivery is open now
	 * $this->is_open
	 *
	 * @return bool
	 */
	public function getIsOpenAttribute()
	{
		$t = time();
		$o = strtotime($this->open);
		$c = strtotime($this->close);
		// Delivery disabled or break enabled or just closed today
		if (
			$this->restaurant->on_delivery != 1 ||
			$this->restaurant->on_break == 1 ||
			$this->closed == 1
		) {
			return false;
		}
		// Is working hours
		if ($t < $c && $t > $o) {
			return true;
		}
		// Its to late or to early :D
		return false;
	}
}
