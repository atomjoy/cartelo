<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Addon;
use Cartelo\Models\AddonGroup;
use Cartelo\Models\Variant;
use Cartelo\Http\Requests\StoreAddonGroupRequest;
use Cartelo\Http\Requests\UpdateAddonGroupRequest;
use Cartelo\Http\Resources\AddonGroupCollection;
use Cartelo\Http\Resources\AddonGroupResource;
use Illuminate\Support\Facades\DB;

class AddonGroupController extends Controller
{
	/**
	 * AddonGroup controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(AddonGroup::class, 'addongroup');
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

		$a = AddonGroup::where(
			DB::raw("CONCAT_WS(' ',name,size,about)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return new AddonGroupCollection($a);
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
	 * @param  \Cartelo\Http\Requests\StoreAddonGroupRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreAddonGroupRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		AddonGroup::withTrashed()->updateOrCreate([
			'name' => $v['name'],
			'size' => $v['size']
		], $v);

		return response()->success("The addongroup has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\AddonGroup  $addongroup
	 * @return \Illuminate\Http\Response
	 */
	public function show(AddonGroup $addongroup)
	{
		return new AddonGroupResource($addongroup);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\AddonGroup  $addongroup
	 * @return \Illuminate\Http\Response
	 */
	public function edit(AddonGroup $addongroup)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateAddonGroupRequest  $request
	 * @param  \Cartelo\Models\AddonGroup  $addongroup
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateAddonGroupRequest $request, AddonGroup $addongroup)
	{
		$v = $request->validated();
		$addongroup->fill($v);
		$addongroup->save();

		return response()->success("The addongroup has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\AddonGroup  $addongroup
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(AddonGroup $addongroup)
	{
		$addongroup->delete();

		return response()->success("The addongroup has been deleted");
	}

	/**
	 * Create addon in addonsgroup.
	 *
	 * @param  \Cartelo\Http\Requests\StoreVariantRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function attachAddon(AddonGroup $addongroup, Addon $addon)
	{
		$addongroup->addons()->attach($addon);

		return response()->success("The addongroup addon has been created");
	}

	/**
	 * Delete addon from addongroups.
	 *
	 * @param  \Cartelo\Http\Requests\StoreVariantRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function detachAddon(AddonGroup $addongroup, Addon $addon)
	{
		$addongroup->addons()->detach($addon);

		return response()->success("The addongroup addon has been deleted");
	}

	/**
	 * Create variant addonsgroup.
	 *
	 * @param  \Cartelo\Http\Requests\StoreVariantRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function attachVariant(AddonGroup $addongroup, Variant $variant)
	{
		$addongroup->variants()->attach($variant);

		return response()->success("The variant group has been created");
	}

	/**
	 * Delete variant addongroups.
	 *
	 * @param  \Cartelo\Http\Requests\StoreVariantRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function detachVariant(AddonGroup $addongroup, Variant $variant)
	{
		$addongroup->variants()->detach($variant);

		return response()->success("The variant group has been deleted");
	}
}
