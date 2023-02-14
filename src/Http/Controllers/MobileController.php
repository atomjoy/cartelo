<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Mobile;
use Cartelo\Http\Requests\StoreMobileRequest;
use Cartelo\Http\Requests\UpdateMobileRequest;
use Cartelo\Http\Resources\MobileCollection;
use Cartelo\Http\Resources\MobileResource;
use Illuminate\Support\Facades\DB;

class MobileController extends Controller
{
	/**
	 * Controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Mobile::class, 'mobile');
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

		$a = Mobile::where(
			DB::raw("CONCAT_WS(' ',name,number)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return response()->success((new MobileCollection($a))->response()->getData(true));
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
	 * @param  \Cartelo\Http\Requests\StoreMobileRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreMobileRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		Mobile::withTrashed()->updateOrCreate([
			'restaurant_id' => $v['restaurant_id'],
			'number' => $v['number'],
		], $v);

		return response()->success("The mobile has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Mobile  $mobile
	 * @return \Illuminate\Http\Response
	 */
	public function show(Mobile $mobile)
	{
		return response()->success((new MobileResource($mobile))->response()->getData(true));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Mobile  $mobile
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Mobile $mobile)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateMobileRequest  $request
	 * @param  \Cartelo\Models\Mobile  $mobile
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateMobileRequest $request, Mobile $mobile)
	{
		$v = $request->validated();
		$mobile->fill($v);
		$mobile->save();

		return response()->success("The mobile has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Mobile  $mobile
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Mobile $mobile)
	{
		$mobile->delete();

		return response()->success("The mobile has been deleted");
	}
}
