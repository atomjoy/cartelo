<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class StoreMobileRequest extends FormRequest
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
			'restaurant_id' => 'required|numeric',
			'number' => [
				'required',
				Rule::unique('mobiles')->where(function ($query) {
					return $query->where('restaurant_id', request()->input('restaurant_id'));
				})->whereNull('deleted_at')
			],
			'prefix' => 'required|max:10',
			'name' => 'required|max:50',
			'sorting' => 'sometimes|boolean',
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
					'restaurant_id', 'name', 'number', 'prefix', 'sorting', 'visible'
				])->toArray()
			)
		);
	}
}
