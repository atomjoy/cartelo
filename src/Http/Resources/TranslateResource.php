<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TranslateResource extends JsonResource
{
	// Change 'data to 'translate' in json response
	// public static $wrap = 'translate';

	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return parent::toArray($request);
	}
}
