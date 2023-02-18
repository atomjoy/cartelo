<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Variant;
use Cartelo\Http\Requests\StoreVariantRequest;
use Cartelo\Http\Requests\UpdateVariantRequest;
use Cartelo\Http\Resources\VariantCollection;
use Cartelo\Http\Resources\VariantResource;
use Illuminate\Support\Facades\DB;

class VariantController extends Controller
{
	/**
	 * Variant controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Variant::class, 'variant');
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

		$a = Variant::with(['product'])->where(
			DB::raw("CONCAT_WS(' ',size,price,price_sale)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')
			->simplePaginate($this->perpage())
			->withQueryString();

		return new VariantCollection($a);
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
	 * @param  \Cartelo\Http\Requests\StoreVariantRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreVariantRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		Variant::updateOrCreate([
			'product_id' => $v['product_id'],
			'size' => $v['size']
		], $v);

		return response()->success("The variant has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Variant  $variant
	 * @return \Illuminate\Http\Response
	 */
	public function show(Variant $variant)
	{
		return new VariantResource($variant);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Variant  $variant
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Variant $variant)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateVariantRequest  $request
	 * @param  \Cartelo\Models\Variant  $variant
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateVariantRequest $request, Variant $variant)
	{
		$v = $request->validated();
		$variant->fill($v);
		$variant->save();

		return response()->success("The variant has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Variant  $variant
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Variant $variant)
	{
		$variant->delete();

		return response()->success("The variant has been deleted");
	}
}
