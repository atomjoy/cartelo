<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
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
		$arr['size'] = trans_db($this->size);
		$arr['addongroups'] = new AddonGroupCollection($this->groups);
		return $arr;
	}
}
