<?php

namespace Cartelo\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cartelo\Models\CartVariant;
use Cartelo\Models\CartVariantAddon;
use Cartelo\Models\Coupon;
use Cartelo\Models\Area;

class Cart extends Model
{
	use HasFactory, SoftDeletes;

	public $incrementing = false;

	protected $primaryKey = 'id';

	protected $keyType = 'string';

	protected $guarded = [];

	protected $hidden = [
		'updated_at',
		'deleted_at',
	];

	protected $casts = [
		'created_at' => 'datetime:Y-m-d H:i:s',
	];

	public $delivery_method = 'home';

	public $error_message = null;

	function area()
	{
		return $this->belongsTo(Area::class);
	}

	function coupon()
	{
		return $this->belongsTo(Coupon::class);
	}

	function user()
	{
		return $this->belongsTo(User::class);
	}

	function variants()
	{
		return $this->hasMany(CartVariant::class);
	}

	function disableCart()
	{
		$this->active = 0;
		$this->save();
	}

	function addDeliveryArea(Area $area)
	{
		if ($this->active == 1) {
			$this->update(['area_id' => $area->id]);
		}
	}

	function addDeliveryMethod($type = 'home')
	{
		if ($this->active == 1) {
			if (in_array($type, ['home', 'pickup', 'restaurant'])) {
				$this->update(['delivery_method' => strtolower($type)]);
			}
		}
	}

	/**
	 * Update logged user id
	 */
	function updateUser()
	{
		if ($this->active == 1) {
			$user = request()->user();
			if ($user instanceof User) {
				$this->update(['user_id' => $user->id]);
			}
		}
	}

	function count()
	{
		return CartVariant::where('cart_id', $this->id)->count();
	}

	function productCost()
	{
		$cost = 0;
		$variants = $this->variants()->get();
		foreach ($variants as $key => $cart_variant) {
			$qty = $cart_variant->quantity;
			$variant = $cart_variant->variant;
			if ($variant->on_sale == 1) {
				$cost += ($variant->price_sale * $qty);
			} else {
				$cost += ($variant->price * $qty);
			}
			//echo "\n Variant " . $variant->price;
			//echo "\n Variant " . $variant->price_sale;
			$addons = $cart_variant->addons()->get();
			foreach ($addons as $key => $cart_variant_addon) {
				//echo "\n Qty " . $qty;
				//echo "\n Addon " . (($cart_variant_addon->addon->price * $cart_variant_addon->quantity) * $qty);
				$cost += (($cart_variant_addon->addon->price * $cart_variant_addon->quantity) * $qty);
			}
		};

		return $cost;
	}

	function packagingCost()
	{
		$cost = 0;
		$variants = $this->variants()->get();
		foreach ($variants as $key => $cart_variant) {
			$qty = $cart_variant->quantity;
			$cost += ($cart_variant->variant->packaging * $qty);
		};
		return $cost;
	}

	function deliveryCost()
	{
		if ($this->delivery_method == 'restaurant') {
			return 0;
		}

		if ($this->area_id != null) {
			$area = $this->area()->first();
			//echo "\n Area ------ " . $area->id;
			if ($area->on_free_from == 1) {
				if ($this->productCost() >= $area->free_from) {
					return 0;
				}
			}
			return $area->cost;
		}
	}

	function isBelowMinimalOrderCost()
	{
		if ($this->delivery_method == 'home') {
			if ($this->area_id != null) {
				$area = $this->area()->first();
				if ($this->productCost() < $area->min_order_cost) {
					return 1;
				}
			}
		}
		return 0;
	}

	function totalCost()
	{
		$this->updateUser();

		if ($this->count() == 0) {
			return '0.00';
		}

		if ($this->delivery_method == 'restaurant') {
			return $this->productCost() - $this->discountCost();
		}

		if ($this->delivery_method == 'pickup') {
			return $this->productCost() + $this->packagingCost() - $this->discountCost();
		}

		return $this->productCost() + $this->packagingCost() + $this->deliveryCost() - $this->discountCost();
	}

	function cashbackReward()
	{
		$cashback = 0;

		// In empty coupon count cashback
		$variants = $this->variants()->get();
		foreach ($variants as $k => $cart_variant) {
			$qty = $cart_variant->quantity;
			// Product variant
			$variant = $cart_variant->variant;
			if ($variant != null) {
				$cashback += ($variant->cashback * $qty);
			}
		}

		return $cashback;
	}

	function discountCost()
	{
		$c = $this->coupon()->first();

		if ($c instanceof Coupon) {
			$discount = $c->countDiscount($this->productCost());
			$this->error_message = $c->error_message;
			return $discount;
		}

		return 0;
	}

	function drawCart()
	{
		return [
			'cart' => $this->with([
				'variants' => function ($query) {
					$query->with([
						'addons' => function ($q) {
							$q->with('addon');
						},
						'variant' => function ($q) {
							$q->with('product');
						}
					]);
				},
				'area', 'coupon'
			])->get()->first()->toArray(),
			'packaging_cost' => number_format($this->packagingCost(), 2, '.', '') ?? 0.00,
			'products_cost' => number_format($this->productCost(), 2, '.', '') ?? 0.00,
			'delivery_cost' => number_format($this->deliveryCost(), 2, '.', '') ?? 0.00,
			'total_cost' => number_format($this->totalCost(), 2, '.', '') ?? 0.00,
			'discount_cost' => number_format($this->discountCost(), 2, '.', '') ?? 0.00,
			'cashback_reward' => number_format($this->cashbackReward(), 2, '.', '') ?? 0.00,
			'below_minimal_order_cost' => $this->isBelowMinimalOrderCost(),
			'error_message' => $this->error_message,
		];
	}

	function addCoupon($code)
	{
		if ($this->active == 1) {
			$code = Str::slug($code);
			$c = Coupon::where('code', $code)->orderBy('id', 'desc')->first();
			if ($c instanceof Coupon) {
				$this->coupon_id = $c->id;
				$this->save();
			} else {
				$this->coupon_id = NULL;
				$this->error_message = trans('Invalid coupon code.');
				$this->save();
			}
		}
	}

	function delCoupon()
	{
		if ($this->active == 1) {
			$this->coupon_id = null;
			$this->error_message = null;
			$this->save();
		}
	}

	/**
	 * @param integer $vid Variant::id
	 * @param integer $qty Quantity
	 */
	function addProduct($vid, $qty = 1)
	{
		if ($this->active == 1) {
			return CartVariant::create([
				'cart_id' => $this->id,
				'variant_id' => $vid,
				'quantity' => $qty,
				'deleted_at' => null
			]);
		}
	}

	/**
	 * @param integer $vid CartVariant::id
	 */
	function delProduct($vid)
	{
		if ($this->active == 1) {
			$cart_variant = $this->variants()->get()->find((int) $vid);
			if ($cart_variant) {
				$cart_variant->forceDelete();
			}
		}
	}

	/**
	 * @param integer $vid CartVariant::id
	 */
	function plusProduct($vid)
	{
		if ($this->active == 1) {
			$cart_variant = $this->variants()->get()->find((int) $vid);
			if ($cart_variant) {
				$cart_variant->quantity = $cart_variant->quantity + 1;
				$cart_variant->save();
			}
		}
	}

	/**
	 * @param integer $vid CartVariant::id
	 */
	function minusProduct($vid)
	{
		if ($this->active == 1) {
			$cart_variant = $this->variants()->get()->find((int) $vid);
			if ($cart_variant) {
				if ($cart_variant->quantity > 1) {
					$cart_variant->quantity = $cart_variant->quantity - 1;
					$cart_variant->save();
				} else {
					$cart_variant->forceDelete();
				}
			}
		}
	}

	/**
	 * @param integer $vid CartVariant::id
	 * @param integer $aid Addon::id
	 * @param integer $qty Quantity
	 */
	function addAddon($vid, $aid, $qty)
	{
		if ($this->active == 1) {
			$variant = $this->variants()->get()->find($vid);
			if ($variant instanceof CartVariant) {
				return CartVariantAddon::updateOrCreate(
					['cart_variant_id' => $vid, 'addon_id' => $aid],
					['quantity' => $qty, 'deleted_at' => null]
				);
			}
		}
	}

	/**
	 * @param integer $vid CartVariant::id
	 * @param integer $aid CartVariantAddon::id
	 */
	function delAddon($vid, $aid)
	{
		if ($this->active == 1) {
			$p = $this->variants()->get()->find((int) $vid);
			if ($p) {
				$a = $p->addons()->get()->find($aid);
				if ($a) {
					$a->forceDelete();
				}
			}
		}
	}

	/**
	 * @param integer $vid CartVariant::id
	 * @param integer $aid CartVariantAddon::id
	 */
	function plusAddon($vid, $aid)
	{
		if ($this->active == 1) {
			$p = $this->variants()->get()->find((int) $vid);
			if ($p) {
				$a = $p->addons()->get()->find($aid);
				if ($a) {
					$a->quantity = $a->quantity + 1;
					$a->save();
				}
			}
		}
	}

	/**
	 * @param integer $vid CartVariant::id
	 * @param integer $aid CartVariantAddon::id
	 */
	function minusAddon($vid, $aid)
	{
		if ($this->active == 1) {
			$p = $this->variants()->get()->find((int) $vid);
			if ($p) {
				$a = $p->addons()->get()->find($aid);
				if ($a) {
					if ($a->quantity > 1) {
						$a->quantity = $a->quantity - 1;
						$a->save();
					} else {
						$a->forceDelete();
					}
				}
			}
		}
	}

	function isExists($variant_id, array $addons_ids = [])
	{
		foreach ($this->variants()->get() as $k => $v) {
			if ($variant_id == $v->variant_id) {
				$list = $v->addons()->get()->pluck('addon_id')->toArray();
				if (count($list) == count($addons_ids)) {
					foreach ($addons_ids as $k => $aid) {
						if (!in_array($aid, $list)) {
							return 0;
						}
					}
					return $v->id;
				}
			}
		}
		return 0;
	}
}
