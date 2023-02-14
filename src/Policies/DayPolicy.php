<?php

namespace Cartelo\Policies;

use App\Models\User;
use Cartelo\Models\Day;
use Webi\Enums\User\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class DayPolicy
{
	use HandlesAuthorization;

	public function before(User $user, $ability)
	{
		if ($user->role == UserRole::ADMIN || $user->role == UserRole::WORKER) {
			return true;
		}
	}

	public function viewAny(?User $user)
	{
		return true;
	}

	public function view(?User $user, Day $day)
	{
		return true;
	}

	public function create(User $user)
	{
		return false;
	}

	public function update(User $user, Day $day)
	{
		return false;
	}

	public function delete(User $user, Day $day)
	{
		return false;
	}

	public function restore(User $user, Day $day)
	{
		return false;
	}

	public function forceDelete(User $user, Day $day)
	{
		return false;
	}
}
