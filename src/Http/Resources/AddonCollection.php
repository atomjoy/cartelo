<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AddonCollection extends ResourceCollection
{
	public static $wrap = null;

	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'addons' => AddonResource::collection($this->collection),
			'meta' => ['count' => $this->collection->count()],
		];

		// return parent::toArray($request);
	}
}
