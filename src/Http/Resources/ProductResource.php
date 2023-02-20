<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		$arr = parent::toArray($request);
		$arr['name'] = trans_db($this->name);
		$arr['about'] = trans_db($this->about);
		$arr['variants'] = new VariantCollection($this->filter_variants);
		$arr['categories'] = new CategoryCollection($this->categories);
		return $arr;
	}
}
