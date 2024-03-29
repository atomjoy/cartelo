<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
			'users' => UserResource::collection($this->collection),
			'meta' => ['count' => $this->collection->count()],
		];

		// return parent::toArray($request);
	}
}
