<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class UpdateDayRequest extends FormRequest
{
	use HasStripTags;

	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true; // Allow all and check policy
	}

	public function rules()
	{
		$day = $this->route('day');

		return [
			'number' => [
				'required',
				Rule::in(['1', '2', '3', '4', '5', '6', '7', 'break']),
				Rule::unique('days')->where(function ($query) use ($day) {
					return $query->where('restaurant_id', $day->restaurant_id);
				})->ignore($day)->whereNull('deleted_at')
			],
			'open' => 'required|regex:/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/',
			'close' => 'required|regex:/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/',
			'closed' => 'sometimes|boolean',
		];
	}

	public function messages()
	{
		return [
			'open' => 'Time format: 08:00:00',
			'close' => 'Time format: 23:00:00',
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
					'number', 'close', 'open', 'closed'
				])->toArray()
			)
		);
	}
}
