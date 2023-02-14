<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Coupon;
use Cartelo\Http\Requests\StoreCouponRequest;
use Cartelo\Http\Requests\UpdateCouponRequest;
use Cartelo\Http\Resources\CouponCollection;
use Cartelo\Http\Resources\CouponResource;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
	/**
	 * Coupon controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Coupon::class, 'coupon');
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

		$a = Coupon::where(
			DB::raw("CONCAT_WS(' ',code,description,type,discount)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return response()->success((new CouponCollection($a))->response()->getData(true));
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
	 * @param  \Cartelo\Http\Requests\StoreCouponRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreCouponRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		$v['used_at'] = NULL;
		Coupon::withTrashed()->updateOrCreate([
			'user_id' => $v['user_id'],
			'code' => $v['code']
		], $v);

		return response()->success("The coupon has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Coupon  $coupon
	 * @return \Illuminate\Http\Response
	 */
	public function show(Coupon $coupon)
	{
		return response()->success((new CouponResource($coupon))->response()->getData(true));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Coupon  $coupon
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Coupon $coupon)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateCouponRequest  $request
	 * @param  \Cartelo\Models\Coupon  $coupon
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateCouponRequest $request, Coupon $coupon)
	{
		$v = $request->validated();
		$coupon->fill($v);
		$coupon->save();

		return response()->success("The coupon has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Coupon  $coupon
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Coupon $coupon)
	{
		$coupon->delete();

		return response()->success("The coupon has been deleted");
	}
}
