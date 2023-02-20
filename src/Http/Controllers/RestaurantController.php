<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Restaurant;
use Cartelo\Http\Requests\StoreRestaurantRequest;
use Cartelo\Http\Requests\UpdateRestaurantRequest;
use Cartelo\Http\Resources\RestaurantCollection;
use Cartelo\Http\Resources\RestaurantResource;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
	/**
	 * Controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Restaurant::class, 'restaurant');
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

		$a = Restaurant::where(
			DB::raw("CONCAT_WS(' ',name,city,address)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return new RestaurantCollection($a);
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
	 * @param  \Cartelo\Http\Requests\StoreRestaurantRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreRestaurantRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		Restaurant::updateOrCreate([
			'name' => $v['name']
		], $v);

		return response()->success("The restaurant has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Restaurant  $restaurant
	 * @return \Illuminate\Http\Response
	 */
	public function show(Restaurant $restaurant)
	{
		return new RestaurantResource($restaurant);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Restaurant  $restaurant
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Restaurant $restaurant)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateRestaurantRequest  $request
	 * @param  \Cartelo\Models\Restaurant  $restaurant
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
	{
		$v = $request->validated();
		$restaurant->fill($v);
		$restaurant->save();

		return response()->success("The restaurant has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Restaurant  $restaurant
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Restaurant $restaurant)
	{
		$restaurant->forceDelete();

		return response()->success("The restaurant has been deleted");
	}
}
