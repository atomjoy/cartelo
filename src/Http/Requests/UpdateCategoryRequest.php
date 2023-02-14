<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class UpdateCategoryRequest extends FormRequest
{
	use HasStripTags;

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		// Get url param
		$category = $this->route('category');

		return [
			'name' => [
				'sometimes',
				'max:255',
				Rule::unique('categories')->ignore($category)->whereNull('deleted_at'),
			],
			'slug' => [
				'sometimes',
				'max:255',
				Rule::unique('categories')->ignore($category)->whereNull('deleted_at'),
			],
			'about' => 'sometimes|max:5000',
			'image_url' => 'sometimes|max:255',
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
					'name', 'slug', 'about', 'image_url', 'visible', 'sorting'
				])->toArray()
			)
		);
	}
}
