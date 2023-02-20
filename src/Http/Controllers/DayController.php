<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Day;
use Cartelo\Http\Requests\StoreDayRequest;
use Cartelo\Http\Requests\UpdateDayRequest;
use Cartelo\Http\Resources\DayCollection;
use Cartelo\Http\Resources\DayResource;
use Illuminate\Support\Facades\DB;

class DayController extends Controller
{
	/**
	 * Day controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Day::class, 'day');
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

		$a = Day::where(
			DB::raw("CONCAT_WS(' ',number,closed)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return new DayCollection($a);
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
	 * @param  \Cartelo\Http\Requests\StoreDayRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreDayRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		Day::updateOrCreate([
			'restaurant_id' => $v['restaurant_id'],
			'number' => $v['number']
		], $v);

		return response()->success("The day has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Day  $day
	 * @return \Illuminate\Http\Response
	 */
	public function show(Day $day)
	{
		return new DayResource($day);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Day  $day
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Day $day)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateDayRequest  $request
	 * @param  \Cartelo\Models\Day  $day
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateDayRequest $request, Day $day)
	{
		$v = $request->validated();
		$day->fill($v);
		$day->save();

		return response()->success("The day has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Day  $day
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Day $day)
	{
		$day->forceDelete();

		return response()->success("The day has been deleted");
	}
}
