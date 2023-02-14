<?php

namespace Cartelo\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Cartelo\Models\Area;
use Cartelo\Models\Cart;
use Cartelo\Models\Addon;
use Cartelo\Models\Variant;
use Cartelo\Models\CartVariant;

class CartController extends Controller
{
	public $delivery_method = 'home';

	function createResponse(array $arr)
	{
		return response()->success($arr); // From webi response macro
	}

	/* Cart json */

	function show(Cart $cart, $delivery_method = null)
	{
		if (in_array($delivery_method, ['home', 'pickup', 'restaurant'])) {
			$cart->addDeliveryMethod($delivery_method);
		}
		return $this->createResponse($cart->drawCart());
	}

	function create()
	{
		$c = Cart::create([
			'id' => Str::uuid(),
			'area_id' => null,
			'user_id' => null,
			'ip' => request()->ip()
		]);

		return $this->createResponse(['cart' => ['cart_id' => $c->id ?? '']]);
	}

	function createWithArea(Area $area)
	{
		$c = Cart::create([
			'id' => Str::uuid(),
			'area_id' => $area->id ?? null,
			'user_id' => null,
			'ip' => request()->ip()
		]);

		return $this->createResponse(['cart' => ['cart_id' => $c->id ?? '']]);
	}

	function updateArea(Cart $cart, Area $area)
	{
		$cart->addDeliveryArea($area);
		return $this->createResponse($cart->drawCart());
	}

	function updateCoupon(Cart $cart, $coupon)
	{
		$cart->addCoupon($coupon);
		return $this->createResponse($cart->drawCart());
	}

	function deleteCoupon(Cart $cart)
	{
		$cart->delCoupon();
		return $this->createResponse($cart->drawCart());
	}

	/* Product */

	function addProduct(Cart $cart, Variant $variant, $qty)
	{
		$cart->addProduct($variant->id, $qty);
		return $this->createResponse($cart->drawCart());
	}

	function delProduct(Cart $cart, $vid)
	{
		$cart->delProduct($vid);
		return $this->createResponse($cart->drawCart());
	}

	function plusProduct(Cart $cart, $vid)
	{
		$cart->plusProduct($vid);
		return $this->createResponse($cart->drawCart());
	}

	function minusProduct(Cart $cart, $vid)
	{
		$cart->minusProduct($vid);
		return $this->createResponse($cart->drawCart());
	}

	/* Addons */

	function addAddon(Cart $cart, CartVariant $variant, Addon $addon, $qty)
	{
		$cart->addAddon($variant->id, $addon->id, $qty);
		return $this->createResponse($cart->drawCart());
	}

	function delAddon(Cart $cart, $vid, $aid)
	{
		$cart->delAddon($vid, $aid);
		return $this->createResponse($cart->drawCart());
	}

	function plusAddon(Cart $cart, $vid, $aid)
	{
		$cart->plusAddon($vid, $aid);
		return $this->createResponse($cart->drawCart());
	}

	function minusAddon(Cart $cart, $vid, $aid)
	{
		$cart->minusAddon($vid, $aid);
		return $this->createResponse($cart->drawCart());
	}
}
