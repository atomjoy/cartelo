<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class UpdateVariantRequest extends FormRequest
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
		$variant = $this->route('variant');

		return [
			'product_id' => null,
			'size' => [
				'sometimes',
				Rule::unique('variants')->where(function ($query) use ($variant) {
					return $query->where('product_id', $variant->product_id);
				})->ignore($variant)->whereNull('deleted_at')
			],
			'price' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'price_sale' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'packaging' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'cashback' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'image' => 'sometimes|image|mimes:png|max:2048',
			'about' => 'sometimes|max:500',
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
					'product_id', 'size', 'price', 'price_sale',
					'packaging', 'cashback', 'image', 'about',
					'on_sale', 'sorting', 'visible'
				])->toArray()
			)
		);
	}
}
