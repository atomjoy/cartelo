<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\User;
use Cartelo\Http\Requests\StoreUserRequest;
use Cartelo\Http\Requests\UpdateUserRequest;
use Cartelo\Http\Resources\UserCollection;
use Cartelo\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	/**
	 * User controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(User::class, 'user');
	}

	/**
	 * Perpage links number
	 */
	function perpage()
	{
		return (int) (request()->input('perpage') ?? config('cartelo.perpage', 12));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$search = "" . app()->request->input('search');

		$a = User::where(
			DB::raw("CONCAT_WS(' ','name', 'email', 'username', 'role', 'mobile', 'website', 'location')"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return response()->success(UserCollection::collection($a));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return [];
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\StoreUserRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreUserRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		User::withTrashed()->updateOrCreate([
			'email' => $v['email'],
			'name' => $v['name'],
			'password' => Hash::make($v['password']),
		], $v);

		return response()->success("The user has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $user)
	{
		return response()->success(new UserResource($user));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function edit(User $user)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateUserRequest  $request
	 * @param  \Cartelo\Models\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateUserRequest $request, User $user)
	{
		$v = $request->validated();
		$user->fill($v);
		$user->save();

		return response()->success("The user has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(User $user)
	{
		$user->delete();

		return response()->success("The user has been deleted");
	}
}
