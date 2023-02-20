<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TranslateCollection extends ResourceCollection
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
			'translates' => TranslateResource::collection($this->collection),
		];

		// return parent::toArray($request);
	}
}
