<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class StoreSocialRequest extends FormRequest
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
			'restaurant_id' => 'required',
			'name' => [
				'required',
				Rule::unique('socials')->where(function ($query) {
					return $query->where('restaurant_id', request()->input('restaurant_id'));
				})->whereNull('deleted_at')
			],
			'link' => 'required',
			'icon' => 'sometimes',
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
					'restaurant_id', 'name', 'link', 'icon'
				])->toArray()
			)
		);
	}
}
