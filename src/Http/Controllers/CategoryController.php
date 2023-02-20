<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Category;
use Cartelo\Models\Product;
use Cartelo\Http\Requests\StoreCategoryRequest;
use Cartelo\Http\Requests\UpdateCategoryRequest;
use Cartelo\Http\Resources\CategoryCollection;
use Cartelo\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
	/**
	 * Category controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Category::class, 'category');
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

		$a = Category::where(
			DB::raw("CONCAT_WS(' ',name,slug)"),
			'regexp',
			str_replace(" ", "|", $search)
		)->orderBy("id", 'desc')->simplePaginate($this->perpage())->withQueryString();

		return new CategoryCollection($a);
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
	 * @param  \Cartelo\Http\Requests\StoreCategoryRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreCategoryRequest $request)
	{
		$v = $request->validated();
		$v['deleted_at'] = NULL;
		Category::updateOrCreate([
			'name' => $v['name'],
			'slug' => $v['slug'],
		], $v);

		return response()->success("The category has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Category  $category
	 * @return \Illuminate\Http\Response
	 */
	public function show(Category $category)
	{
		return new CategoryResource($category);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Category  $category
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Category $category)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateCategoryRequest  $request
	 * @param  \Cartelo\Models\Category  $category
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateCategoryRequest $request, Category $category)
	{
		$v = $request->validated();
		$category->fill($v);
		$category->save();

		return response()->success("The category has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Category  $category
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Category $category)
	{
		$category->forceDelete();

		return response()->success("The category has been deleted");
	}

	/**
	 * Add product to category.
	 *
	 * @param  \Cartelo\Http\Requests\StoreVariantRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function attachProduct(Category $category, Product $product)
	{
		$category->products()->attach($product);

		return response()->success("The category product has been created");
	}

	/**
	 * Delete product from categories.
	 *
	 * @param  \Cartelo\Http\Requests\StoreVariantRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function detachProduct(Category $category, Product $product)
	{
		$category->products()->detach($product);

		return response()->success("The category product has been deleted");
	}
}
