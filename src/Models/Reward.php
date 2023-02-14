<?php

namespace Cartelo\Models;

use App\Models\User;
use Cartelo\Models\Order;
use Database\Factories\RewardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $hidden = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $casts = [
		'created_at' => 'datetime:Y-m-d',
	];

	protected static function newFactory()
	{
		return RewardFactory::new();
	}

	function user()
	{
		return $this->belongsTo(User::class);
	}

	function order()
	{
		return $this->belongsTo(Order::class);
	}
}
