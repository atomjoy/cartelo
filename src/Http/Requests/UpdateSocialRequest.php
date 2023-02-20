<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class UpdateSocialRequest extends FormRequest
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
		$social = $this->route('social');

		return [
			'name' => [
				'sometimes',
				Rule::unique('socials')->where(function ($query) use ($social) {
					return $query->where('restaurant_id', $social->restaurant_id);
				})->ignore($social)->whereNull('deleted_at')
			],
			'link' => 'sometimes|max:500',
			'icon' => 'sometimes|max:500',
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
					'name', 'link', 'icon'
				])->toArray()
			)
		);
	}
}
