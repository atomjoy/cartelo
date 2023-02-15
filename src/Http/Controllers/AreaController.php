<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Area;
use Cartelo\Http\Requests\StoreAreaRequest;
use Cartelo\Http\Requests\UpdateAreaRequest;
use Cartelo\Http\Resources\AreaCollection;
use Cartelo\Http\Resources\AreaResource;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
	/**
	 * Area controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Area::class, 'area');
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

		$a = Area::where(
			DB::raw("CONCAT_WS(' ',name,about)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return new AreaCollection($a);
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
	 * @param  \Cartelo\Http\Requests\StoreAreaRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreAreaRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		Area::withTrashed()->updateOrCreate([
			'restaurant_id' => $v['restaurant_id'],
			'name' => $v['name']
		], $v);

		return response()->success("The area has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Area  $area
	 * @return \Illuminate\Http\Response
	 */
	public function show(Area $area)
	{
		return new AreaResource($area);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Area  $area
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Area $area)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateAreaRequest  $request
	 * @param  \Cartelo\Models\Area  $area
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateAreaRequest $request, Area $area)
	{
		$v = $request->validated();
		if (!empty($v['polygon'])) {
			$area->polygon = $v['polygon'];
		}
		unset($v['polygon']);
		$v['deleted_at'] = NULL;
		$area->fill($v);
		$area->save();

		return response()->success("The area has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Area  $area
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Area $area)
	{
		$area->delete();

		return response()->success("The area has been deleted");
	}
}
