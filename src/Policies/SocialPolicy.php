<?php

namespace Cartelo\Policies;

use App\Models\User;
use Cartelo\Models\Social;
use Webi\Enums\User\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class SocialPolicy
{
	use HandlesAuthorization;

	// Allow only logged admin or worker
	public function before(User $user, $ability)
	{
		// Authenticated roles only: admin and/or worker and/or user
		if ($user->role == UserRole::ADMIN || $user->role == UserRole::WORKER) {
			return true;
		}
	}

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function viewAny(?User $user)
	{
		return true;
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @param  \Cartelo\Models\Social  $social
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function view(?User $user, Social $social)
	{
		return true;
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function create(User $user)
	{
		return false;
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @param  \Cartelo\Models\Social  $social
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function update(User $user, Social $social)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @param  \Cartelo\Models\Social  $social
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function delete(User $user, Social $social)
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @param  \Cartelo\Models\Social  $social
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function restore(User $user, Social $social)
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @param  \Cartelo\Models\Social  $social
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function forceDelete(User $user, Social $social)
	{
		return false;
	}
}
