<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'products' => ProductResource::collection($this->collection),
			'meta' => [
				'count' => $this->collection->count(),
			],
			//'data' => $this->collection,
			// 'paginate' => $this->resource->linkCollection(),
			// 'paginate' => [
			// 	'prev_page' => $this->resource->previousPageUrl(),
			// 	'current_page' => $this->resource->url($this->resource->currentPage()),
			// 	'next_page' => $this->resource->nextPageUrl(),
			// 	'on_first_page' => $this->resource->onFirstPage(),
			// 	'on_last_page' => $this->resource->onLastPage(),
			// 	'per_page' => (int) $this->resource->perPage(),
			// 	'from' => (int) $this->resource->firstItem(),
			// 	'to' => (int) $this->resource->lastItem(),
			// ],
			// 'data' => $this->resource->getData(true)
		];

		// return parent::toArray($request);
	}
}
