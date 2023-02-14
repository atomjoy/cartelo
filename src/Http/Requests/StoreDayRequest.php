<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class StoreDayRequest extends FormRequest
{
	use HasStripTags;

	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		// $day = $this->route('day');
		// $day = Day::find($day);
		// return $d && $this->user()->can('update', $day);

		return true; // Allow all
	}

	public function rules()
	{
		return [
			'restaurant_id' => 'required',
			'number' => [
				'required',
				Rule::in(['1', '2', '3', '4', '5', '6', '7', 'break']),
				Rule::unique('days')->where(function ($query) {
					return $query->where('restaurant_id', request()->input('restaurant_id'));
				})->whereNull('deleted_at')
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
		throw new ApiException($validator->errors()->first(), 422);
	}

	function prepareForValidation()
	{
		$this->merge(
			$this->stripTags(
				collect(request()->json()->all())->only([
					'restaurant_id', 'number', 'close', 'open', 'closed'
				])->toArray()
			)
		);
	}
}
