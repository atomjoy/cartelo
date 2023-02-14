<?php

namespace Cartelo\Models;

use Cartelo\Models\Restaurant;
use Database\Factories\AreaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Area extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $dateFormat = 'Y-m-d H:i:s';

	protected static function newFactory()
	{
		return AreaFactory::new();
	}

	public function restaurant()
	{
		return $this->belongsTo(Restaurant::class);
	}

	/**
	 * Get polygon json
	 */
	function getPolygonAttribute()
	{
		return ($this->selectRaw('ST_AsGeoJSON(polygon) as poly')->where('id', $this->id)->first())->poly ?? null;
	}

	/**
	 * Set polygon geometry from json
	 */
	function setPolygonAttribute($geo_json)
	{
		$this->attributes['polygon'] = DB::raw("ST_GeomFromGeoJSON('" . $geo_json . "')");
	}

	/**
	 * Get area from location
	 */
	static function getAreaFromLocation($lng, $lat)
	{
		return Area::selectRaw('*')->whereRaw('ST_CONTAINS(polygon, POINT(:lng,:lat))', ['lng' => $lng, 'lat' => $lat])->first();
	}

	// Geojson sample
	static function geoJsonPolygonSample()
	{
		return '{"type": "Polygon", "coordinates": [[[21.01752050781249, 52.16553065086626], [21.018035491943348, 52.12265533376558], [21.079490264892566, 52.12697633873785], [21.06421240234374, 52.143413406069634], [21.052024444580066, 52.154473402050264], [21.043269714355457, 52.15647444111914], [21.032626708984363, 52.16711003359743], [21.01752050781249, 52.16553065086626]]]}';
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
