<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Addon;
use Cartelo\Http\Requests\StoreAddonRequest;
use Cartelo\Http\Requests\UpdateAddonRequest;
use Cartelo\Http\Resources\AddonCollection;
use Cartelo\Http\Resources\AddonResource;
use Illuminate\Support\Facades\DB;

class AddonController extends Controller
{
	/**
	 * Addon controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Addon::class, 'addon');
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

		$a = Addon::where(
			DB::raw("CONCAT_WS(' ',name,price)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return  new AddonCollection($a);
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
	 * @param  \Cartelo\Http\Requests\StoreAddonRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreAddonRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		Addon::withTrashed()->updateOrCreate([
			'name' => $v['name'],
			'price' => $v['price']
		], $v);

		return response()->success("The addon has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Addon  $addon
	 * @return \Illuminate\Http\Response
	 */
	public function show(Addon $addon)
	{
		return new AddonResource($addon);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Addon  $addon
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Addon $addon)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateAddonRequest  $request
	 * @param  \Cartelo\Models\Addon  $addon
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateAddonRequest $request, Addon $addon)
	{
		$v = $request->validated();
		$addon->fill($v);
		$addon->save();

		return response()->success("The addon has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Addon  $addon
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Addon $addon)
	{
		$addon->delete();

		return response()->success("The addon has been deleted");
	}
}
