<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class StoreCategoryRequest extends FormRequest
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
				'required',
				Rule::unique('categories')->whereNull('deleted_at')
			],
			'slug' => [
				'required',
				Rule::unique('categories')->whereNull('deleted_at')
			],
			'about' => 'sometimes|max:5000',
			'image_url' => 'sometimes|max:500',
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
					'slug' => Str::slug($this->slug),
					'name', 'about', 'image_url', 'visible', 'sorting'
				])->toArray()
			)
		);
	}
}
