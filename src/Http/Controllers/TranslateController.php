<?php

namespace Cartelo\Http\Controllers;

use App\Http\Controllers\Controller;
use Cartelo\Models\Translate;
use Cartelo\Http\Requests\StoreTranslateRequest;
use Cartelo\Http\Requests\UpdateTranslateRequest;
use Cartelo\Http\Resources\TranslateCollection;
use Cartelo\Http\Resources\TranslateResource;
use Illuminate\Support\Facades\DB;

class TranslateController extends Controller
{
	/**
	 * Translate controller
	 */
	public function __construct()
	{
		// Authorize with policy (class, url_param)
		$this->authorizeResource(Translate::class, 'translate');
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
		$key = "" . app()->request->input('key');

		$q = Translate::query();

		if (!empty($search)) {
			$q->where(
				DB::raw("CONCAT_WS(' ',locale,value)"),
				'regexp',
				str_replace(" ", "|", $search)
			);
		}

		if (!empty($key)) {
			$q->where('key', $key);
		}

		$q->orderBy("id", 'desc');

		$paginator = $q->simplePaginate($this->perpage())->withQueryString();

		return  new TranslateCollection($paginator);
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
	 * @param  \Cartelo\Http\Requests\StoreTranslateRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreTranslateRequest $request)
	{
		$v = $request->validated();
		Translate::updateOrCreate([
			'locale' => $v['locale'],
			'key' => $v['key']
		], $v);

		return response()->success("The translate has been created");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \Cartelo\Models\Translate  $translate
	 * @return \Illuminate\Http\Response
	 */
	public function show(Translate $translate)
	{
		return new TranslateResource($translate);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \Cartelo\Models\Translate  $translate
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Translate $translate)
	{
		return [];
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Cartelo\Http\Requests\UpdateTranslateRequest  $request
	 * @param  \Cartelo\Models\Translate  $translate
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateTranslateRequest $request, Translate $translate)
	{
		$v = $request->validated();
		$translate->fill($v);
		$translate->save();

		return response()->success("The translate has been updated");
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \Cartelo\Models\Translate  $translate
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Translate $translate)
	{
		$translate->forceDelete();

		return response()->success("The translate has been deleted");
	}
}
