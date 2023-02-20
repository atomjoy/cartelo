<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class UpdateUserRequest extends FormRequest
{
	use HasStripTags;

	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$user = $this->route('user');

		return [
			'name' => 'sometimes|min:3|max:50',
			'username' => [
				'sometimes',
				Rule::unique('users')->ignore($user)->whereNull('deleted_at')
			],
			'role' => [
				'sometimes', Rule::in(['worker', 'user']),
			],
			'mobile' => 'sometimes|numeric',
			'mobile_prefix' => 'sometimes|numeric',
			'website' => 'sometimes|max:255',
			'location' => 'sometimes|max:255',
			'code' => 'sometimes|max:255',
			'locale' => 'sometimes|max:255',
			'newsletter_on' => 'sometimes|boolean',
			'image' => 'sometimes|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
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
					'role', 'name', 'username', 'mobile_prefix', 'mobile',
					'code', 'locale', 'website', 'location', 'image'
				])->toArray()
			)
		);
	}
}
