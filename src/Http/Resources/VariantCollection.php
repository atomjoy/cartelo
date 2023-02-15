<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VariantCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return ['variants' => VariantResource::collection($this->collection)];

		// return parent::toArray($request);
	}
}
