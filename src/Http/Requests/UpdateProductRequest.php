<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class UpdateProductRequest extends FormRequest
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
		$product = $this->route('product');

		return [
			'name' => [
				'sometimes',
				Rule::unique('products')->ignore($product)->whereNull('deleted_at')
			],
			'slug' => [
				'sometimes',
				Rule::unique('products')->ignore($product)->whereNull('deleted_at')
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
		throw new ApiException($validator->errors()->first(), 422);
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

		$this->merge($this->stripTags([
			'slug' => Str::slug(strip_tags($this->slug))
		]));
	}
}
