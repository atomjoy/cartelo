<?php

namespace Cartelo\Models;

use App\Models\User;
use Carbon\Carbon;
use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
	use HasFactory, SoftDeletes;

	protected $guarded = [];

	protected $hidden = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $casts = [
		'created_at' => 'datetime:Y-m-d',
	];

	public $error_message = null;

	protected static function newFactory()
	{
		return CouponFactory::new();
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	protected function maxAmountDiscount($productCost)
	{
		$percent = $this->max_order_percent;

		if ($percent > 0 && $percent <= 100) {
			$max = round($productCost * ($percent / 100), 2);
			return number_format($max, 2, '.', '');
		}

		return $productCost;
	}

	function countDiscount($productCost)
	{
		// Disabled
		if ($this->active != true) {
			$this->error_message = "Coupon has been expired.";
			return 0;
		}
		// Used
		if (!empty($this->used_at)) {
			$this->error_message = "Coupon has been used.";
			return 0;
		}
		// Expires
		$expired = Carbon::createFromFormat('Y-m-d H:i:s', $this->expired_at);
		if ($expired->lt(now())) {
			$this->error_message = "Coupon has been expired.";
			return 0;
		}
		// Invalid owner
		if ($this->user instanceof User) {
			if ($this->user != auth()->user()) {
				$this->error_message = "Invalid coupon owner.";
				return 0;
			}
		}
		// Amount
		if ($this->type == 'amount') {
			if ($this->discount > $this->maxAmountDiscount($productCost)) {
				$this->error_message = trans("Coupon with a limit of up to") . " " . $this->max_order_percent . trans("% of the order amount, add more products to redeem the coupon in full.");
				// Max allowed % of the order
				if ($this->max_order_percent > 0 && $this->max_order_percent <= 100) {
					$discount_max = round($productCost * ($this->max_order_percent / 100), 2);
					return number_format($discount_max, 2, '.', '');
				}
				return 0;
			}
			return number_format($this->discount, 2, '.', '');
		}
		// Percent
		if ($this->type == 'percent') {
			if ($this->discount <= 0 || $this->discount > 100) {
				$this->error_message = "Invalid coupon value.";
				return 0;
			}
			$discount = round($productCost * ($this->discount / 100), 2);
			return number_format($discount, 2, '.', '');
		}

		return 0;
	}

	function setUsed()
	{
		// Only if coupon is dedicated for one client
		if ($this->user_id > 0) {
			$this->used_at = now()->format('Y-m-d H:i:s');
		}
	}
}
