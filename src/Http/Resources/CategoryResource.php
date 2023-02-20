<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
		$arr['products'] =  $this->products;
		return $arr;
	}
}
