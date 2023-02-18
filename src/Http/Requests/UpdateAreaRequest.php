<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class UpdateAreaRequest extends FormRequest
{
	use HasStripTags;

	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true; // Allow all, and check with AreaPolicy::before()
	}

	public function rules()
	{
		// Get url param
		$area = $this->route('area');

		return [
			'restaurant_id' => 'sometimes|numeric',
			'name' => [
				'sometimes', 'min:5', 'max:100',
				Rule::unique('areas')->where(function ($query) use ($area) {
					return $query->where('restaurant_id', $area->restaurant_id);
				})->ignore($area)->whereNull('deleted_at'),
			],
			'about' => 'sometimes|max:255',
			'cost' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'min_order_cost' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'free_from' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'on_free_from' => 'sometimes|boolean',
			'time' => 'sometimes|numeric|gte:0',
			'sorting' => 'sometimes|numeric',
			'visible' => 'sometimes|boolean',
			'polygon' => 'sometimes|json',
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
					'restaurant_id', 'name', 'about', 'polygon',
					'cost', 'min_order_cost', 'on_free_from', 'free_from',
					'time', 'visible', 'sorting'
				])->toArray()
			)
		);
	}

	public function messages()
	{
		return [
			// 'name.unique' => 'Couple name and restaurant_id has to be unique.',
		];
	}
}
