<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class StoreTranslateRequest extends FormRequest
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
			'locale' => 'required|string|min:2|max:2',
			'key' => [
				'required', 'min:1',
				Rule::unique('translates')->where(function ($query) {
					return $query->where('locale', request()->input('locale'));
				})
			],
			'value' => 'required|string|min:1'
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
					'locale', 'key', 'value'
				])->toArray()
			)
		);
	}
}
