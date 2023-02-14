<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Order;
use Cartelo\Http\Requests\StoreOrderRequest;
use Cartelo\Http\Requests\UpdateOrderRequest;
use Cartelo\Http\Resources\OrderCollection;
use Cartelo\Http\Resources\OrderResource;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
	/**
	 * Order controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Order::class, 'order');
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

		$a = Order::where(
			DB::raw("CONCAT_WS(' ',cost,status)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return response()->success((new OrderCollection($a))->response()->getData(true));
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
	 * @param  \Cartelo\Http\Requests\StoreOrderRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreOrderRequest $request)
	{
		$v = $request->validated();
		Order::create($v);

		return response()->success("The order has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function show(Order $order)
	{
		return response()->success((new OrderResource($order))->response()->getData(true));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Order $order)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateOrderRequest  $request
	 * @param  \Cartelo\Models\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateOrderRequest $request, Order $order)
	{
		$v = $request->validated();
		$order->fill($v);
		$order->save();

		return response()->success("The order has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Order $order)
	{
		$order->delete();

		return response()->success("The order has been deleted");
	}
}
