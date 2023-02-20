<?php

namespace Cartelo\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Cartelo\Traits\HasStripTags;

class UpdateCouponRequest extends FormRequest
{
	use HasStripTags;

	protected $stopOnFirstFailure = true;

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$coupon = $this->route('coupon');

		return [
			'code' => [
				'sometimes',
				Rule::unique('coupons')->where(function ($query) use ($coupon) {
					$id = $coupon->user_id ?? null;
					return $query->where('user_id', $id);
				})->ignore($coupon)->whereNull('deleted_at')
			],
			'type' => ['sometimes', Rule::in(['amount', 'percent'])],
			'discount' => 'sometimes|numeric|gte:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
			'max_order_percent' => 'sometimes|numeric|gte:0|lte:100',
			'description' => 'sometimes|max:500',
			'used_at' => 'sometimes|date_format:Y-m-d H:i:s',
			'expired_at' => 'sometimes|date_format:Y-m-d H:i:s',
			'active' => 'sometimes|boolean',
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
					'user_id',
					'code', 'type', 'discount', 'max_order_percent',
					'description', 'used_at', 'expired_at', 'active'
				])->toArray()
			)
		);
	}
}
