<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class StoreProductRequest extends FormRequest
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
				Rule::unique('products')->whereNull('deleted_at')
			],
			'slug' => [
				'required',
				Rule::unique('products')->whereNull('deleted_at')
			],
			'about' => 'sometimes|max:500',
			'image' => 'sometimes|image|mimes:png|max:2048',
			'on_sale' => 'sometimes|boolean',
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
					'slug', 'name', 'image', 'about', 'on_sale', 'sorting', 'visible'
				])->toArray()
			)
		);

		$this->merge([
			'slug' => Str::slug(strip_tags($this->slug))
		]);
	}
}
