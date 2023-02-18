<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Social;
use Cartelo\Http\Requests\StoreSocialRequest;
use Cartelo\Http\Requests\UpdateSocialRequest;
use Cartelo\Http\Resources\SocialCollection;
use Cartelo\Http\Resources\SocialResource;
use Illuminate\Support\Facades\DB;

class SocialController extends Controller
{
	/**
	 * Controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Social::class, 'social');
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

		$a = Social::where(
			DB::raw("CONCAT_WS(' ',name,link)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return new SocialCollection($a);
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
	 * @param  \Cartelo\Http\Requests\StoreSocialRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreSocialRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		Social::updateOrCreate([
			'restaurant_id' => $v['restaurant_id'],
			'name' => $v['name']
		], $v);

		return response()->success("The social link has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Social  $social
	 * @return \Illuminate\Http\Response
	 */
	public function show(Social $social)
	{
		return new SocialResource($social);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Social  $social
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Social $social)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateSocialRequest  $request
	 * @param  \Cartelo\Models\Social  $social
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateSocialRequest $request, Social $social)
	{
		$v = $request->validated();
		$social->fill($v);
		$social->save();

		return response()->success("The social has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Social  $social
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Social $social)
	{
		$social->delete();

		return response()->success("The social has been deleted");
	}
}
