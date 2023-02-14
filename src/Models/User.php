<?php

namespace Cartelo\Models;

use Cartelo\Http\Resources\UserResource;
use Cartelo\Models\Order;
use Cartelo\Models\Coupon;
use Webi\Models\WebiUser;

class CarteloUser extends WebiUser
{
	function orders()
	{
		return $this->hasMany(Order::class);
	}

	function coupons()
	{
		return $this->hasMany(Coupon::class);
	}

	function profile()
	{
		return new UserResource($this);
	}
}
