<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class StoreAddonRequest extends FormRequest
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
			'price' => 'required|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'name' => [
				'required', 'max:100',
				Rule::unique('addons')->where(function ($query) {
					return $query->where('price', request()->input('price'));
				})->whereNull('deleted_at')
			],
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
					'name', 'price', 'sorting', 'visible'
				])->toArray()
			)
		);
	}
}
