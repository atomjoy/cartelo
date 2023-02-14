<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class StoreRestaurantRequest extends FormRequest
{
	use HasStripTags;

	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'name' => [
				'required', 'max:255',
				Rule::unique('restaurants')->whereNull('deleted_at'),
			],
			'city' => 'required|max:255',
			'address' => 'required|max:255',
			'country' => 'sometimes|max:255',
			'mobile' => 'sometimes|max:255',
			'email' => 'sometimes|max:255',
			'website' => 'sometimes|max:255',
			'about' => 'sometimes|max:500',
			'lng' => 'sometimes|numeric|regex:/^-?[0-9]+(?:.[0-9]{6,})?$/',
			'lat' => 'sometimes|numeric|regex:/^-?[0-9]+(?:.[0-9]{6,})?$/',
			'on_pay_money' => 'sometimes|boolean',
			'on_pay_card' => 'sometimes|boolean',
			'on_pay_online' => 'sometimes|boolean',
			'on_delivery' => 'sometimes|boolean',
			'on_break' => 'sometimes|boolean',
			'break_to' => 'sometimes|date_format:H:i:s',
			'delivery_home' => 'sometimes|boolean',
			'delivery_pickup' => 'sometimes|boolean',
			'delivery_restaurant' => 'sometimes|boolean',
			'sorting' => 'sometimes|numeric',
			'visible' => 'sometimes|boolean',
		];
	}

	public function failedValidation(Validator $validator)
	{
		throw new ApiException($validator->errors()->first(), 422);
	}

	function prepareForValidation()
	{
		$this->merge(
			$this->stripTags(
				collect(request()->json()->all())->only([
					'name', 'city', 'address', 'country', 'mobile', 'email', 'website', 'about',
					'lng', 'lat', 'on_pay_money', 'on_pay_card', 'on_pay_online',
					'on_delivery', 'on_break', 'break_to',
					'delivery_pickup', 'delivery_restaurant', 'delivery_home',
					'sorting', 'visible',
				])->toArray()
			)
		);
	}
}
