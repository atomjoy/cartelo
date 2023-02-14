<?php

namespace Cartelo\Policies;

use App\Models\User;
use Cartelo\Models\Coupon;
use Webi\Enums\User\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class CouponPolicy
{
	use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if ($user->role == UserRole::ADMIN || $user->role == UserRole::WORKER) {
			return true;
		}
	}

	public function viewAny(User $user)
	{
		return false;
	}

	public function view(User $user, Coupon $coupon)
	{
		return false;
	}

	public function create(User $user)
	{
		return false;
	}

	public function update(User $user, Coupon $coupon)
	{
		return false;
	}

	public function delete(User $user, Coupon $coupon)
	{
		return false;
	}

	public function restore(User $user, Coupon $coupon)
	{
		return false;
	}

	public function forceDelete(User $user, Coupon $coupon)
	{
		return false;
	}
}
