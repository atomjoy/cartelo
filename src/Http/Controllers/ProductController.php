<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Http\Requests\StoreProductRequest;
use Cartelo\Http\Requests\UpdateProductRequest;
use Cartelo\Http\Resources\ProductCollection;
use Cartelo\Http\Resources\ProductResource;
use Cartelo\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
	/**
	 * Product controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Product::class, 'product');
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

		// $q = Product::query();
		// $q->with('variants');
		// if (!empty($search)) {
		// 	// $q->where('slug', 'LIKE', "%" . $search . "%");
		// 	$q->whereRaw("CONCAT_WS(' ',name,slug,about) REGEXP '" . str_replace(" ", "|", trim($search)) . "'");
		// 	//$q->where('slug', 'REGEXP', str_replace(" ", "|", $search));
		// 	//$q->where('about', 'REGEXP', str_replace(" ", "|", $search));
		// }
		// $a = $q->orderBy("id", 'desc')->paginate($this->perpage())->withQueryString();

		$a = Product::with('variants')->where(
			DB::raw("CONCAT_WS(' ',name,slug,about)"),
			'REGEXP',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->paginate($this->perpage())->withQueryString();

		return new ProductCollection($a);
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
	 * @param  \Cartelo\Http\Requests\StoreProductRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreProductRequest $request)
	{

		$v = $request->validated();
		$v['deleted_at'] = NULL;
		$v['image'] = NULL;
		$product = Product::withTrashed()->updateOrCreate([
			'name' => $v['name'],
			'slug' => $v['slug'],
		], $v);

		if ($request->hasFile('image')) {
			Storage::disk('public')->putFileAs(
				'products',
				$request->file('image'),
				$product->id . '_product.png'
			);
			// Save
			$product->image = 'products/' . $product->id . '_product.png';
			$product->save();
		}

		return response()->success([
			'message' => trans("The product has been created"),
			'product' => $product
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function show(Product $product)
	{
		return new ProductResource($product);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Product $product)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateProductRequest  $request
	 * @param  \Cartelo\Models\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateProductRequest $request, Product $product)
	{
		$v = $request->validated();
		$v['image'] = NULL;
		$product->fill($v);
		$product->save();

		if ($request->hasFile('image')) {
			Storage::disk('public')->putFileAs(
				'products',
				$request->file('image'),
				$product->id . '_product.png'
			);
			// Save
			$product->image = 'products/' . $product->id . '_product.png';
			$product->save();
		}

		return response()->success("The product has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Product  $product
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Product $product)
	{
		$product->delete();

		return response()->success("The product has been deleted");
	}
}
