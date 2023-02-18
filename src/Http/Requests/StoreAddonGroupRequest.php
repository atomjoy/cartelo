<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class StoreAddonGroupRequest extends FormRequest
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
				'required', 'max:100',
				Rule::unique('addon_groups')->where(function ($query) {
					return $query->where('size', request()->input('size'));
				})->whereNull('deleted_at')
			],
			'size' => [
				'required',
				Rule::in(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'])
			],
			'about' => 'sometimes|max:500',
			'multiple' => 'sometimes|boolean',
			'required' => 'sometimes|boolean',
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
					'name', 'size', 'about', 'multiple', 'required',
					'sorting', 'visible'
				])->toArray()
			)
		);
	}
}
