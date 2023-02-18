<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Cartelo\Exceptions\ApiException;
use Cartelo\Traits\HasStripTags;

class StoreCouponRequest extends FormRequest
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
			'user_id' => 'sometimes|nullable|numeric',
			'code' => [
				'required',
				Rule::unique('coupons')->where(function ($query) {
					$id = request()->input('user_id') ?? null;
					return $query->where('user_id', $id);
				})->whereNull('deleted_at')
			],
			'type' => ['required', Rule::in(['amount', 'percent'])],
			'discount' => 'required|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'max_order_percent' => 'required|numeric|gte:0|lte:100',
			'description' => 'sometimes|max:500',
			'expired_at' => 'required|nullable|date_format:Y-m-d H:i:s',
			'used_at' => 'sometimes|nullable|date_format:Y-m-d H:i:s',
			'active' => 'sometimes|boolean',
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
					'user_id',
					'code', 'type', 'discount', 'max_order_percent',
					'description', 'used_at', 'expired_at', 'active'
				])->toArray()
			)
		);
	}
}
