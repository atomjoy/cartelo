<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class UpdateMobileRequest extends FormRequest
{
	use HasStripTags;

	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		// Get url param
		$mobile = $this->route('mobile');

		return [
			'restaurant_id' => 'sometimes|numeric',
			'number' => [
				'sometimes',
				Rule::unique('mobiles')->where(function ($query) use ($mobile) {
					return $query->where('restaurant_id', $mobile->restaurant_id);
				})->ignore($mobile)->whereNull('deleted_at')
			],
			'prefix' => 'sometimes|max:10',
			'name' => 'sometimes|max:50',
			'sorting' => 'sometimes|boolean',
			'visible' => 'sometimes|boolean',
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
					'restaurant_id', 'name', 'number', 'prefix', 'sorting', 'visible'
				])->toArray()
			)
		);
	}
}
