<?php

namespace Cartelo\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'username' => $this->username,
			'mobile' => $this->mobile,
			'mobile_prefix' => $this->mobile_prefix,
			'location' => $this->location,
			'website' => $this->website,
			'image' => $this->image,
			'code' => $this->code,
			'locale' => $this->locale,
			'newsletter_on' => $this->newsletter_on,
			'created_at' => (string) $this->created_at,
			'updated_at' => (string) $this->updated_at,
		];

		return parent::toArray($request);
	}
}
