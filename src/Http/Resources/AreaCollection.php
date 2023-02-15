<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AreaCollection extends ResourceCollection
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
			'areas' => AreaResource::collection($this->collection),
			'meta' => ['count' => $this->collection->count()],
		];

		// return parent::toArray($request);
	}
}
