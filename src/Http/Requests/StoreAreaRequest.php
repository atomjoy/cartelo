<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class StoreAreaRequest extends FormRequest
{
	use HasStripTags;

	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true; // Allow all, and check with AreaPolicy::before() and in controller constructor.
	}

	public function rules()
	{
		$restaurant_id = request()->input('restaurant_id');

		return [
			'restaurant_id' => 'required|numeric',
			'name' => [
				'required', 'min:5', 'max:100',
				Rule::unique('areas')->where(function ($query) use ($restaurant_id) {
					return $query->where('restaurant_id', $restaurant_id);
				})->whereNull('deleted_at')
			],
			'about' => 'required',
			'cost' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'min_order_cost' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'free_from' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'on_free_from' => 'sometimes|boolean',
			'time' => 'sometimes|numeric|gte:0',
			'sorting' => 'sometimes|numeric',
			'visible' => 'sometimes|boolean',
			'polygon' => 'required|json',
		];
	}

	public function failedValidation(Validator $validator)
	{
		throw new ValidationException($validator, response()->json([
			'message' => $validator->errors()->first()
		], 422));
	}

	function prepareForValidation()
	{
		$this->merge(
			$this->stripTags(
				collect(request()->json()->all())->only([
					'restaurant_id', 'name', 'about', 'polygon',
					'min_order_cost', 'cost', 'on_free_from', 'free_from',
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
