<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class UpdateAddonRequest extends FormRequest
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
		$addon = $this->route('addon');

		return [
			'name' => [
				'sometimes', 'max:100',
				Rule::unique('addons')->where(function ($query) {
					return $query->where('price', request()->input('price'));
				})->ignore($addon)->whereNull('deleted_at')
			],
			'price' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'sorting' => 'sometimes|numeric',
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
					'name', 'price', 'sorting', 'visible'
				])->toArray()
			)
		);
	}
}
